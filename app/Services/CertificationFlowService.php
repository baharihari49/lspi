<?php

namespace App\Services;

use App\Models\Apl01Form;
use App\Models\Apl02Unit;
use App\Models\Assessment;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificationFlowService
{
    protected Apl02GeneratorService $apl02Generator;
    protected AssessmentSchedulerService $assessmentScheduler;
    protected CertificateGeneratorService $certificateGenerator;
    protected FlowNotificationService $notificationService;

    public function __construct(
        Apl02GeneratorService $apl02Generator,
        AssessmentSchedulerService $assessmentScheduler,
        CertificateGeneratorService $certificateGenerator,
        FlowNotificationService $notificationService
    ) {
        $this->apl02Generator = $apl02Generator;
        $this->assessmentScheduler = $assessmentScheduler;
        $this->certificateGenerator = $certificateGenerator;
        $this->notificationService = $notificationService;
    }

    /**
     * Handle APL-01 approval - trigger APL-02 generation.
     */
    public function handleApl01Approved(Apl01Form $apl01): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'units' => [],
        ];

        try {
            DB::beginTransaction();

            // Check if already processed
            if ($apl01->apl02_generated_at) {
                $result['message'] = 'APL-02 sudah dibuat sebelumnya.';
                DB::rollBack();
                return $result;
            }

            // Generate APL-02 units
            $generatedUnits = $this->apl02Generator->generateFromApl01($apl01);

            // Update APL-01 status
            $apl01->apl02_generated_at = now();
            $apl01->flow_status = 'apl02_generated';
            $apl01->save();

            DB::commit();

            // Send notifications (outside transaction)
            $this->notificationService->notifyApl02Generated($apl01, $generatedUnits);

            $result['success'] = true;
            $result['message'] = 'APL-02 berhasil dibuat dengan ' . count($generatedUnits) . ' unit kompetensi.';
            $result['units'] = $generatedUnits;

            Log::info('APL-01 approved flow completed', [
                'apl01_id' => $apl01->id,
                'units_generated' => count($generatedUnits),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $result['message'] = 'Gagal membuat APL-02: ' . $e->getMessage();
            Log::error('APL-01 approved flow failed', [
                'apl01_id' => $apl01->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $result;
    }

    /**
     * Handle APL-02 unit completion - check if all units complete and schedule assessment.
     */
    public function handleApl02UnitCompleted(Apl02Unit $unit): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'all_complete' => false,
            'assessment' => null,
        ];

        try {
            $apl01 = $unit->apl01Form;

            if (!$apl01) {
                $result['message'] = 'APL-01 form tidak ditemukan.';
                return $result;
            }

            // Notify about unit review
            $this->notificationService->notifyApl02UnitReviewed($unit);

            // Check if all units are complete
            $allComplete = $this->assessmentScheduler->areAllApl02UnitsComplete($apl01);
            $result['all_complete'] = $allComplete;

            if ($allComplete) {
                // Update APL-01 flow status
                $apl01->flow_status = 'apl02_completed';
                $apl01->save();

                // Auto-schedule assessment
                $assessment = $this->assessmentScheduler->checkAndScheduleAssessment($apl01);

                if ($assessment) {
                    $result['assessment'] = $assessment;
                    $result['message'] = 'Semua unit APL-02 kompeten. Asesmen telah dijadwalkan.';

                    // Send notification
                    $this->notificationService->notifyAssessmentScheduled($assessment);
                } else {
                    $result['message'] = 'Semua unit APL-02 kompeten. Asesmen sudah ada/dijadwalkan sebelumnya.';
                }

                Log::info('APL-02 completion flow completed', [
                    'apl01_id' => $apl01->id,
                    'assessment_scheduled' => $assessment ? true : false,
                ]);
            } else {
                $unitStats = $this->assessmentScheduler->getCompletedUnitCount($apl01);
                $result['message'] = "Unit direview. {$unitStats['competent']}/{$unitStats['total']} unit kompeten.";
            }

            $result['success'] = true;

        } catch (\Exception $e) {
            $result['message'] = 'Gagal memproses: ' . $e->getMessage();
            Log::error('APL-02 completion flow failed', [
                'unit_id' => $unit->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $result;
    }

    /**
     * Handle assessment result approval - generate certificate if competent.
     */
    public function handleAssessmentApproved(Assessment $assessment): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'certificate' => null,
        ];

        try {
            // Check if eligible for certificate
            if (!$this->certificateGenerator->canGenerateCertificate($assessment)) {
                if ($assessment->overall_result !== 'competent') {
                    $result['message'] = 'Hasil asesmen bukan kompeten. Sertifikat tidak dapat diterbitkan.';
                } else {
                    $result['message'] = 'Sertifikat sudah diterbitkan atau asesmen belum disetujui.';
                }
                return $result;
            }

            DB::beginTransaction();

            // Generate certificate
            $certificate = $this->certificateGenerator->generateFromAssessment($assessment);

            DB::commit();

            // Send notification
            $this->notificationService->notifyCertificateIssued($certificate);

            $result['success'] = true;
            $result['message'] = 'Sertifikat berhasil diterbitkan: ' . $certificate->certificate_number;
            $result['certificate'] = $certificate;

            Log::info('Assessment approved flow completed', [
                'assessment_id' => $assessment->id,
                'certificate_number' => $certificate->certificate_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $result['message'] = 'Gagal menerbitkan sertifikat: ' . $e->getMessage();
            Log::error('Assessment approved flow failed', [
                'assessment_id' => $assessment->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $result;
    }

    /**
     * Get flow status summary for an APL-01 form.
     */
    public function getFlowStatus(Apl01Form $apl01): array
    {
        $unitStats = $this->assessmentScheduler->getCompletedUnitCount($apl01);

        $assessment = Assessment::where('apl01_form_id', $apl01->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $certificate = null;
        if ($assessment) {
            $certificate = Certificate::where('assessment_result_id', $assessment->id)->first();
        }

        return [
            'apl01' => [
                'id' => $apl01->id,
                'form_number' => $apl01->form_number,
                'status' => $apl01->status,
                'approved' => $apl01->status === 'approved',
            ],
            'apl02' => [
                'generated' => $apl01->apl02_generated_at !== null,
                'generated_at' => $apl01->apl02_generated_at,
                'total_units' => $unitStats['total'],
                'competent_units' => $unitStats['competent'],
                'pending_units' => $unitStats['pending'],
                'not_yet_competent_units' => $unitStats['not_yet_competent'],
                'all_complete' => $unitStats['total'] > 0 && $unitStats['competent'] === $unitStats['total'],
                'progress_percentage' => $unitStats['total'] > 0
                    ? round(($unitStats['competent'] / $unitStats['total']) * 100)
                    : 0,
            ],
            'assessment' => [
                'exists' => $assessment !== null,
                'id' => $assessment?->id,
                'number' => $assessment?->assessment_number,
                'status' => $assessment?->status,
                'scheduled_date' => $assessment?->scheduled_date,
                'result' => $assessment?->overall_result,
                'approved' => $assessment?->status === 'approved',
            ],
            'certificate' => [
                'exists' => $certificate !== null,
                'id' => $certificate?->id,
                'number' => $certificate?->certificate_number,
                'status' => $certificate?->status,
                'issued_at' => $certificate?->issue_date,
                'valid_until' => $certificate?->valid_until,
            ],
            'current_stage' => $this->determineCurrentStage($apl01, $unitStats, $assessment, $certificate),
            'flow_status' => $apl01->flow_status,
        ];
    }

    /**
     * Determine current stage of certification flow.
     */
    protected function determineCurrentStage(
        Apl01Form $apl01,
        array $unitStats,
        ?Assessment $assessment,
        ?Certificate $certificate
    ): string {
        if ($certificate) {
            return 'certificate_issued';
        }

        if ($assessment && $assessment->status === 'approved') {
            return 'assessment_approved';
        }

        if ($assessment && in_array($assessment->status, ['completed', 'under_review', 'verified'])) {
            return 'assessment_completed';
        }

        if ($assessment && $assessment->status === 'in_progress') {
            return 'assessment_in_progress';
        }

        if ($assessment && $assessment->status === 'scheduled') {
            return 'assessment_scheduled';
        }

        if ($unitStats['total'] > 0 && $unitStats['competent'] === $unitStats['total']) {
            return 'apl02_completed';
        }

        if ($unitStats['total'] > 0 && $unitStats['competent'] > 0) {
            return 'apl02_in_progress';
        }

        if ($apl01->apl02_generated_at) {
            return 'apl02_generated';
        }

        if ($apl01->status === 'approved') {
            return 'apl01_approved';
        }

        return 'apl01_pending';
    }

    /**
     * Manually trigger APL-02 generation.
     */
    public function triggerApl02Generation(Apl01Form $apl01): array
    {
        if ($apl01->status !== 'approved') {
            return [
                'success' => false,
                'message' => 'APL-01 harus disetujui terlebih dahulu.',
            ];
        }

        return $this->handleApl01Approved($apl01);
    }

    /**
     * Manually trigger assessment scheduling.
     */
    public function triggerAssessmentScheduling(Apl01Form $apl01, ?int $daysFromNow = null): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'assessment' => null,
        ];

        try {
            if ($daysFromNow !== null) {
                $this->assessmentScheduler->setScheduleDays($daysFromNow);
            }

            $assessment = $this->assessmentScheduler->scheduleAssessment($apl01);

            $this->notificationService->notifyAssessmentScheduled($assessment);

            $result['success'] = true;
            $result['message'] = 'Asesmen berhasil dijadwalkan.';
            $result['assessment'] = $assessment;

        } catch (\Exception $e) {
            $result['message'] = 'Gagal menjadwalkan asesmen: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Manually trigger certificate generation.
     */
    public function triggerCertificateGeneration(Assessment $assessment): array
    {
        return $this->handleAssessmentApproved($assessment);
    }
}
