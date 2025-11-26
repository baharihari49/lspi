<?php

namespace App\Listeners;

use App\Events\AssessmentResultApproved;
use App\Services\CertificationFlowService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GenerateCertificateFromApprovedAssessment implements ShouldQueue
{
    use InteractsWithQueue;

    protected CertificationFlowService $flowService;

    /**
     * Create the event listener.
     */
    public function __construct(CertificationFlowService $flowService)
    {
        $this->flowService = $flowService;
    }

    /**
     * Handle the event.
     */
    public function handle(AssessmentResultApproved $event): void
    {
        $assessment = $event->assessment;

        Log::info('Processing AssessmentResultApproved event', [
            'assessment_id' => $assessment->id,
            'assessment_number' => $assessment->assessment_number,
            'result' => $assessment->overall_result,
        ]);

        $result = $this->flowService->handleAssessmentApproved($assessment);

        if ($result['success']) {
            Log::info('Certificate generated successfully', [
                'assessment_id' => $assessment->id,
                'certificate_number' => $result['certificate']->certificate_number,
            ]);
        } else {
            Log::info('Certificate generation result', [
                'assessment_id' => $assessment->id,
                'message' => $result['message'],
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(AssessmentResultApproved $event, \Throwable $exception): void
    {
        Log::error('Failed to generate certificate from approved assessment', [
            'assessment_id' => $event->assessment->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
