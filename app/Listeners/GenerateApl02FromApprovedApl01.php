<?php

namespace App\Listeners;

use App\Events\Apl01Approved;
use App\Services\CertificationFlowService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GenerateApl02FromApprovedApl01 implements ShouldQueue
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
    public function handle(Apl01Approved $event): void
    {
        $apl01 = $event->apl01Form;

        Log::info('Processing Apl01Approved event', [
            'apl01_id' => $apl01->id,
            'form_number' => $apl01->form_number,
        ]);

        $result = $this->flowService->handleApl01Approved($apl01);

        if ($result['success']) {
            Log::info('APL-02 generation completed successfully', [
                'apl01_id' => $apl01->id,
                'units_count' => count($result['units'] ?? []),
            ]);
        } else {
            Log::warning('APL-02 generation failed or skipped', [
                'apl01_id' => $apl01->id,
                'message' => $result['message'],
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Apl01Approved $event, \Throwable $exception): void
    {
        Log::error('Failed to generate APL-02 from approved APL-01', [
            'apl01_id' => $event->apl01Form->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
