<?php

namespace App\Http\Controllers\Admin;

use App\Events\AssessmentResultApproved;
use App\Http\Controllers\Controller;
use App\Models\ResultApproval;
use App\Models\AssessmentResult;
use App\Models\User;
use Illuminate\Http\Request;

class ResultApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ResultApproval::with(['assessmentResult.assessee', 'assessmentResult.scheme', 'approver'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by decision
        if ($request->filled('decision')) {
            $query->where('decision', $request->decision);
        }

        // Filter by approver role
        if ($request->filled('approver_role')) {
            $query->where('approver_role', $request->approver_role);
        }

        // Filter by approval level
        if ($request->filled('approval_level')) {
            $query->where('approval_level', $request->approval_level);
        }

        // Filter by approver
        if ($request->filled('approver_id')) {
            $query->where('approver_id', $request->approver_id);
        }

        // Filter overdue
        if ($request->filled('is_overdue')) {
            $query->where('is_overdue', $request->is_overdue);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('assessmentResult', function($q) use ($search) {
                    $q->where('result_number', 'like', "%{$search}%")
                      ->orWhere('certificate_number', 'like', "%{$search}%");
                })
                ->orWhereHas('approver', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $approvals = $query->paginate(15);

        return view('admin.result-approval.index', compact('approvals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get assessment result from query parameter
        $resultId = $request->get('assessment_result_id');

        if (!$resultId) {
            return redirect()->route('admin.assessment-results.index')
                ->with('error', 'Assessment Result ID diperlukan');
        }

        $assessmentResult = AssessmentResult::with(['assessee', 'scheme'])->findOrFail($resultId);

        // Get approvers
        $approvers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['assessor', 'admin', 'super_admin']);
        })->orderBy('name')->get();

        return view('admin.result-approval.create', compact('assessmentResult', 'approvers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_result_id' => 'required|exists:assessment_results,id',
            'approver_id' => 'required|exists:users,id',
            'approval_level' => 'required|integer|min:1',
            'sequence_order' => 'nullable|integer|min:0',
            'approver_role' => 'required|in:lead_assessor,technical_reviewer,quality_assurance,certification_manager,director,external_verifier',
            'due_date' => 'nullable|date',
            'comments' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';
        $validated['assigned_at'] = now();
        $validated['sequence_order'] = $validated['sequence_order'] ?? 0;

        // Check if due date is in the past
        if (isset($validated['due_date']) && $validated['due_date'] < now()->format('Y-m-d')) {
            $validated['is_overdue'] = true;
        }

        $approval = ResultApproval::create($validated);

        return redirect()->route('admin.result-approval.show', $approval)
            ->with('success', 'Approval berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(ResultApproval $resultApproval)
    {
        $resultApproval->load([
            'assessmentResult.assessee',
            'assessmentResult.scheme',
            'assessmentResult.leadAssessor',
            'approver',
            'delegatedTo',
            'previousApproval'
        ]);

        return view('admin.result-approval.show', compact('resultApproval'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResultApproval $resultApproval)
    {
        // Only allow editing if pending or in_review
        if (!in_array($resultApproval->status, ['pending', 'in_review'])) {
            return redirect()->route('admin.result-approval.show', $resultApproval)
                ->with('error', 'Hanya approval dengan status pending atau in_review yang dapat diubah');
        }

        $resultApproval->load([
            'assessmentResult.assessee',
            'assessmentResult.scheme'
        ]);

        // Get approvers
        $approvers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['assessor', 'admin', 'super_admin']);
        })->orderBy('name')->get();

        return view('admin.result-approval.edit', compact('resultApproval', 'approvers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResultApproval $resultApproval)
    {
        // Only allow editing if pending or in_review
        if (!in_array($resultApproval->status, ['pending', 'in_review'])) {
            return redirect()->route('admin.result-approval.show', $resultApproval)
                ->with('error', 'Hanya approval dengan status pending atau in_review yang dapat diubah');
        }

        $validated = $request->validate([
            'approver_id' => 'required|exists:users,id',
            'approval_level' => 'required|integer|min:1',
            'sequence_order' => 'nullable|integer|min:0',
            'approver_role' => 'required|in:lead_assessor,technical_reviewer,quality_assurance,certification_manager,director,external_verifier',
            'due_date' => 'nullable|date',
            'comments' => 'nullable|string',
        ]);

        $validated['sequence_order'] = $validated['sequence_order'] ?? 0;

        // Check if due date is in the past
        if (isset($validated['due_date']) && $validated['due_date'] < now()->format('Y-m-d')) {
            $validated['is_overdue'] = true;
        } else {
            $validated['is_overdue'] = false;
        }

        $resultApproval->update($validated);

        return redirect()->route('admin.result-approval.show', $resultApproval)
            ->with('success', 'Approval berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResultApproval $resultApproval)
    {
        // Only allow deletion if pending
        if ($resultApproval->status !== 'pending') {
            return redirect()->route('admin.result-approval.index')
                ->with('error', 'Hanya approval dengan status pending yang dapat dihapus');
        }

        $resultApproval->delete();

        return redirect()->route('admin.result-approval.index')
            ->with('success', 'Approval berhasil dihapus');
    }

    /**
     * Process approval decision
     */
    public function processDecision(Request $request, ResultApproval $resultApproval)
    {
        $validated = $request->validate([
            'decision' => 'required|in:approve,reject,request_revision,defer',
            'comments' => 'required|string',
            'recommendations' => 'nullable|string',
            'checklist' => 'nullable|array',
            'issues_identified' => 'nullable|array',
            'required_changes' => 'nullable|array',
            'conditions' => 'nullable|string',
        ]);

        // Update status based on decision
        $statusMap = [
            'approve' => 'approved',
            'reject' => 'rejected',
            'request_revision' => 'returned_for_revision',
            'defer' => 'in_review',
        ];

        $validated['status'] = $statusMap[$validated['decision']];
        $validated['decision_at'] = now();
        $validated['reviewed_at'] = now();

        // Calculate review duration if assigned_at exists
        if ($resultApproval->assigned_at) {
            $validated['review_duration_minutes'] = now()->diffInMinutes($resultApproval->assigned_at);
        }

        $resultApproval->update($validated);

        // Update assessment result approval status
        $this->updateAssessmentResultStatus($resultApproval);

        return redirect()->route('admin.result-approval.show', $resultApproval)
            ->with('success', 'Decision berhasil diproses');
    }

    /**
     * Delegate approval to another user
     */
    public function delegate(Request $request, ResultApproval $resultApproval)
    {
        $validated = $request->validate([
            'delegated_to' => 'required|exists:users,id',
            'delegation_notes' => 'nullable|string',
        ]);

        $validated['delegated_at'] = now();

        $resultApproval->update($validated);

        return redirect()->route('admin.result-approval.show', $resultApproval)
            ->with('success', 'Approval berhasil didelegasikan');
    }

    /**
     * Update assessment result approval status based on all approvals
     */
    private function updateAssessmentResultStatus(ResultApproval $resultApproval)
    {
        $assessmentResult = $resultApproval->assessmentResult;

        // Get all approvals for this result
        $allApprovals = ResultApproval::where('assessment_result_id', $assessmentResult->id)->get();

        // Check if any approval is rejected
        if ($allApprovals->where('status', 'rejected')->count() > 0) {
            $assessmentResult->update(['approval_status' => 'rejected']);
            return;
        }

        // Check if any approval requires revision
        if ($allApprovals->where('status', 'returned_for_revision')->count() > 0) {
            $assessmentResult->update(['approval_status' => 'requires_revision']);
            return;
        }

        // Check if all approvals are approved
        if ($allApprovals->where('status', 'approved')->count() === $allApprovals->count()) {
            $assessmentResult->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Update the assessment status to approved and dispatch event if competent
            $assessment = $assessmentResult->assessment;
            if ($assessment) {
                $assessment->status = 'approved';
                $assessment->overall_result = $assessmentResult->final_result;
                $assessment->save();

                // If result is competent, dispatch event to generate certificate
                if ($assessmentResult->final_result === 'competent') {
                    event(new AssessmentResultApproved($assessment));
                }
            }

            return;
        }

        // Otherwise, keep as pending
        $assessmentResult->update(['approval_status' => 'pending']);
    }
}
