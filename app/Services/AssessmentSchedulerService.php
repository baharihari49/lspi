<?php

namespace App\Services;

use App\Models\Apl01Form;
use App\Models\Apl02Unit;
use App\Models\Assessment;
use App\Models\Event;
use App\Models\EventSession;
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
            ->whereIn('status', ['draft', 'pending_confirmation', 'scheduled', 'in_progress'])
            ->exists();
    }

    /**
     * Schedule a new assessment for the APL-01 form.
     */
    public function scheduleAssessment(Apl01Form $apl01, ?int $daysFromNow = null): Assessment
    {
        // Get available session from event
        $sessionId = $this->getAvailableSessionId($apl01);
        $session = $sessionId ? EventSession::find($sessionId) : null;

        // Use session date if available, otherwise use default schedule
        $scheduledDate = $session
            ? $session->session_date
            : now()->addDays($daysFromNow ?? $this->defaultScheduleDays);

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
            'event_session_id' => $sessionId,
            'apl01_form_id' => $apl01->id,
            'lead_assessor_id' => $leadAssessorId,
            'assessment_method' => 'portfolio', // Default method
            'assessment_type' => 'initial', // Initial certification
            'scheduled_date' => $scheduledDate,
            'scheduled_time' => $session?->start_time,
            'venue' => $this->getVenue($apl01),
            'tuk_id' => $tukId,
            'status' => 'pending_confirmation', // Menunggu konfirmasi admin
            'auto_scheduled' => true,
            'created_by' => auth()->id() ?? 1,
        ]);

        // Increment session participant count
        if ($sessionId) {
            $this->incrementSessionParticipants($sessionId);
        }

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
     * Get lead assessor ID from event.
     * Returns the Assessor ID (not EventAssessor ID) for the lead assessor of the event.
     */
    protected function getLeadAssessorId(Apl01Form $apl01): ?int
    {
        if ($apl01->event_id) {
            $event = Event::with(['assessors.assessor'])->find($apl01->event_id);
            if ($event && $event->assessors->isNotEmpty()) {
                // Get lead assessor first (role = 'lead'), or fall back to first confirmed assessor
                $eventAssessor = $event->assessors->firstWhere('role', 'lead')
                    ?? $event->assessors->firstWhere('status', 'confirmed')
                    ?? $event->assessors->first();

                // Return the actual Assessor ID, not the EventAssessor ID
                return $eventAssessor->assessor_id;
            }
        }

        return null;
    }

    /**
     * Get TUK ID from event.
     * Gets the actual TUK ID from the event's primary TUK assignment.
     */
    protected function getTukId(Apl01Form $apl01): ?int
    {
        if ($apl01->event_id) {
            $event = Event::with(['tuks.tuk'])->find($apl01->event_id);
            if ($event && $event->tuks->isNotEmpty()) {
                // Get primary TUK first, or fall back to first TUK
                $eventTuk = $event->tuks->firstWhere('is_primary', true)
                    ?? $event->tuks->first();

                // Return the actual TUK ID, not the EventTuk ID
                return $eventTuk->tuk_id;
            }
        }

        return null;
    }

    /**
     * Get venue from event or TUK.
     * Uses the event's location or the TUK's address.
     */
    protected function getVenue(Apl01Form $apl01): ?string
    {
        if ($apl01->event_id) {
            $event = Event::with(['tuks.tuk'])->find($apl01->event_id);
            if ($event) {
                // First try to use event's own location
                if ($event->location) {
                    return $event->location . ($event->location_address ? ' - ' . $event->location_address : '');
                }

                // Fall back to TUK address
                if ($event->tuks->isNotEmpty()) {
                    $eventTuk = $event->tuks->firstWhere('is_primary', true)
                        ?? $event->tuks->first();

                    if ($eventTuk->tuk) {
                        $tuk = $eventTuk->tuk;
                        return $tuk->name . ' - ' . ($tuk->address ?? '');
                    }
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

    /**
     * Get available session ID from event.
     * Returns the first session with available capacity.
     */
    protected function getAvailableSessionId(Apl01Form $apl01): ?int
    {
        if (!$apl01->event_id) {
            return null;
        }

        $event = Event::with('sessions')->find($apl01->event_id);
        if (!$event || !$event->sessions || $event->sessions->isEmpty()) {
            return null;
        }

        // Find session with available capacity, ordered by date/time
        $availableSession = $event->sessions
            ->where('is_active', true)
            ->filter(function ($session) {
                // Session date must be today or in the future
                return $session->session_date >= now()->toDateString()
                    && $session->current_participants < $session->max_participants;
            })
            ->sortBy(['session_date', 'start_time'])
            ->first();

        return $availableSession?->id;
    }

    /**
     * Increment session participant count.
     */
    protected function incrementSessionParticipants(int $sessionId): void
    {
        EventSession::where('id', $sessionId)->increment('current_participants');
    }

    /**
     * Decrement session participant count (for cancellation).
     */
    public function decrementSessionParticipants(int $sessionId): void
    {
        EventSession::where('id', $sessionId)
            ->where('current_participants', '>', 0)
            ->decrement('current_participants');
    }
}
