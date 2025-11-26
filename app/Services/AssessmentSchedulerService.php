<?php

namespace App\Services;

use App\Models\Apl01Form;
use App\Models\Apl02Unit;
use App\Models\Assessment;
use App\Models\Event;
use Illuminate\Support\Collection;

class AssessmentSchedulerService
{
    /**
     * Default days to schedule assessment after APL-02 completion.
     */
    protected int $defaultScheduleDays = 7;

    /**
     * Check if all APL-02 units are complete (all competent).
     */
    public function areAllApl02UnitsComplete(Apl01Form $apl01): bool
    {
        $units = Apl02Unit::where('apl01_form_id', $apl01->id)->get();

        if ($units->isEmpty()) {
            return false;
        }

        // All units must have assessment_result = 'competent'
        return $units->every(fn($unit) => $unit->assessment_result === 'competent');
    }

    /**
     * Check and schedule assessment if APL-02 is complete.
     */
    public function checkAndScheduleAssessment(Apl01Form $apl01): ?Assessment
    {
        // Check if APL-02 units are all competent
        if (!$this->areAllApl02UnitsComplete($apl01)) {
            return null;
        }

        // Check if assessment already scheduled
        if ($this->hasScheduledAssessment($apl01)) {
            return null;
        }

        return $this->scheduleAssessment($apl01);
    }

    /**
     * Check if an assessment is already scheduled for this APL-01.
     */
    public function hasScheduledAssessment(Apl01Form $apl01): bool
    {
        return Assessment::where('apl01_form_id', $apl01->id)
            ->whereIn('status', ['draft', 'scheduled', 'in_progress'])
            ->exists();
    }

    /**
     * Schedule a new assessment for the APL-01 form.
     */
    public function scheduleAssessment(Apl01Form $apl01, ?int $daysFromNow = null): Assessment
    {
        $scheduledDate = now()->addDays($daysFromNow ?? $this->defaultScheduleDays);

        // Get lead assessor from event or default
        $leadAssessorId = $this->getLeadAssessorId($apl01);

        // Get TUK from event
        $tukId = $this->getTukId($apl01);

        $assessment = Assessment::create([
            'assessment_number' => $this->generateAssessmentNumber(),
            'title' => 'Asesmen ' . ($apl01->scheme->name ?? 'Sertifikasi'),
            'description' => 'Asesmen otomatis dari APL-01: ' . $apl01->form_number,
            'assessee_id' => $apl01->assessee_id,
            'scheme_id' => $apl01->scheme_id,
            'event_id' => $apl01->event_id,
            'apl01_form_id' => $apl01->id,
            'lead_assessor_id' => $leadAssessorId,
            'assessment_method' => 'portfolio', // Default method
            'assessment_type' => 'initial', // Initial certification
            'scheduled_date' => $scheduledDate,
            'venue' => $this->getVenue($apl01),
            'tuk_id' => $tukId,
            'status' => 'scheduled',
            'auto_scheduled' => true,
            'created_by' => auth()->id() ?? 1,
        ]);

        // Update APL-01 flow status
        $apl01->updateFlowStatus('assessment_scheduled');

        return $assessment;
    }

    /**
     * Generate a unique assessment number.
     */
    protected function generateAssessmentNumber(): string
    {
        $year = date('Y');
        $month = date('m');

        $lastAssessment = Assessment::where('assessment_number', 'like', "ASM-{$year}{$month}-%")
            ->orderBy('assessment_number', 'desc')
            ->first();

        if ($lastAssessment) {
            $lastNumber = (int) substr($lastAssessment->assessment_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'ASM-' . $year . $month . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get lead assessor ID from event or default.
     */
    protected function getLeadAssessorId(Apl01Form $apl01): ?int
    {
        if ($apl01->event_id) {
            $event = Event::with('assessors')->find($apl01->event_id);
            if ($event && $event->assessors->isNotEmpty()) {
                // Return first assessor marked as lead, or first assessor
                $leadAssessor = $event->assessors->firstWhere('pivot.is_lead', true);
                return $leadAssessor ? $leadAssessor->id : $event->assessors->first()->id;
            }
        }

        return null;
    }

    /**
     * Get TUK ID from event.
     */
    protected function getTukId(Apl01Form $apl01): ?int
    {
        if ($apl01->event_id) {
            $event = Event::with('tuks')->find($apl01->event_id);
            if ($event && $event->tuks->isNotEmpty()) {
                return $event->tuks->first()->id;
            }
        }

        return null;
    }

    /**
     * Get venue from event or TUK.
     */
    protected function getVenue(Apl01Form $apl01): ?string
    {
        if ($apl01->event_id) {
            $event = Event::with('tuks')->find($apl01->event_id);
            if ($event) {
                // Use event location or TUK address
                if ($event->tuks->isNotEmpty()) {
                    $tuk = $event->tuks->first();
                    return $tuk->name . ' - ' . ($tuk->address ?? '');
                }
            }
        }

        return null;
    }

    /**
     * Get the count of completed APL-02 units.
     */
    public function getCompletedUnitCount(Apl01Form $apl01): array
    {
        $units = Apl02Unit::where('apl01_form_id', $apl01->id)->get();

        return [
            'total' => $units->count(),
            'competent' => $units->where('assessment_result', 'competent')->count(),
            'not_yet_competent' => $units->where('assessment_result', 'not_yet_competent')->count(),
            'pending' => $units->whereNull('assessment_result')->count() +
                        $units->where('assessment_result', 'pending')->count(),
        ];
    }

    /**
     * Set custom schedule days.
     */
    public function setScheduleDays(int $days): self
    {
        $this->defaultScheduleDays = $days;
        return $this;
    }
}
