<?php

namespace App\Listeners;

use App\Events\Apl02AllUnitsCompetent;
use App\Services\CertificationFlowService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ScheduleAssessmentFromCompletedApl02 implements ShouldQueue
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
    public function handle(Apl02AllUnitsCompetent $event): void
    {
        $apl01 = $event->apl01Form;

        Log::info('Processing Apl02AllUnitsCompetent event', [
            'apl01_id' => $apl01->id,
            'form_number' => $apl01->form_number,
        ]);

        $result = $this->flowService->handleApl02UnitCompleted($event->lastUnit);

        if ($result['success'] && $result['assessment']) {
            Log::info('Assessment scheduled successfully', [
                'apl01_id' => $apl01->id,
                'assessment_id' => $result['assessment']->id,
                'assessment_number' => $result['assessment']->assessment_number,
            ]);
        } else {
            Log::info('Assessment scheduling result', [
                'apl01_id' => $apl01->id,
                'message' => $result['message'],
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Apl02AllUnitsCompetent $event, \Throwable $exception): void
    {
        Log::error('Failed to schedule assessment from completed APL-02', [
            'apl01_id' => $event->apl01Form->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
