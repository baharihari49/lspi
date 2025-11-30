<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl01Form;
use App\Models\Assessment;
use App\Services\CertificationFlowService;
use App\Services\Apl02GeneratorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CertificationFlowController extends Controller
{
    protected CertificationFlowService $flowService;

    public function __construct(CertificationFlowService $flowService)
    {
        $this->flowService = $flowService;
    }

    /**
     * Get flow status for an APL-01 form.
     */
    public function getFlowStatus(Apl01Form $apl01): JsonResponse
    {
        $status = $this->flowService->getFlowStatus($apl01);

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }

    /**
     * Show flow status page for an APL-01 form.
     */
    public function show(Apl01Form $apl01)
    {
        $flowStatus = $this->flowService->getFlowStatus($apl01);

        return view('admin.certification-flow.show', [
            'apl01' => $apl01,
            'flowStatus' => $flowStatus,
        ]);
    }

    /**
     * Manually trigger APL-02 Form generation (per-Scheme - NEW FLOW).
     */
    public function generateApl02(Apl01Form $apl01): RedirectResponse
    {
        try {
            $generatorService = app(Apl02GeneratorService::class);
            $apl02Form = $generatorService->generateApl02Form($apl01);

            return redirect()
                ->route('admin.apl02-forms.show', $apl02Form)
                ->with('success', 'APL-02 Form berhasil dibuat dengan nomor ' . $apl02Form->form_number);
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.apl01.show', $apl01)
                ->with('error', 'Gagal membuat APL-02 Form: ' . $e->getMessage());
        }
    }

    /**
     * Manually trigger assessment scheduling.
     */
    public function scheduleAssessment(Request $request, Apl01Form $apl01): JsonResponse
    {
        $daysFromNow = $request->input('days_from_now', 7);

        $result = $this->flowService->triggerAssessmentScheduling($apl01, $daysFromNow);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'assessment' => $result['assessment'] ? [
                'id' => $result['assessment']->id,
                'number' => $result['assessment']->assessment_number,
                'scheduled_date' => $result['assessment']->scheduled_date?->format('Y-m-d'),
            ] : null,
        ]);
    }

    /**
     * Manually trigger certificate generation.
     */
    public function generateCertificate(Assessment $assessment): JsonResponse
    {
        $result = $this->flowService->triggerCertificateGeneration($assessment);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'certificate' => $result['certificate'] ? [
                'id' => $result['certificate']->id,
                'number' => $result['certificate']->certificate_number,
            ] : null,
        ]);
    }

    /**
     * Dashboard showing all APL-01 with their flow status.
     */
    public function dashboard(Request $request)
    {
        $query = Apl01Form::with(['assessee', 'scheme', 'event'])
            ->whereIn('status', ['approved', 'completed']);

        // Filter by flow status
        if ($request->filled('flow_status')) {
            $query->where('flow_status', $request->flow_status);
        }

        // Filter by scheme
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('form_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhereHas('assessee', function ($q2) use ($search) {
                        $q2->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        $apl01Forms = $query->latest()->paginate(20);

        // Get flow status for each form
        $flowStatuses = [];
        foreach ($apl01Forms as $apl01) {
            $flowStatuses[$apl01->id] = $this->flowService->getFlowStatus($apl01);
        }

        return view('admin.certification-flow.dashboard', [
            'apl01Forms' => $apl01Forms,
            'flowStatuses' => $flowStatuses,
        ]);
    }
}
