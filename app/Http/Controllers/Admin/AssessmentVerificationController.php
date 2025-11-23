<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentVerification;
use App\Models\Assessment;
use App\Models\AssessmentUnit;
use App\Models\User;
use Illuminate\Http\Request;

class AssessmentVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessmentVerification::with(['assessment.assessee', 'assessmentUnit', 'verifier'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('assessment_id')) {
            $query->where('assessment_id', $request->assessment_id);
        }

        if ($request->filled('verifier_id')) {
            $query->where('verifier_id', $request->verifier_id);
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('verification_level')) {
            $query->where('verification_level', $request->verification_level);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('verification_number', 'like', "%{$search}%");
            });
        }

        $verifications = $query->paginate(15);

        return view('admin.assessment-verification.index', compact('verifications'));
    }

    public function create(Request $request)
    {
        $assessments = Assessment::with('assessee')->orderBy('created_at', 'desc')->limit(50)->get();
        $verifiers = User::withRole('assessor')->orderBy('name')->get();

        $selectedAssessment = null;
        if ($request->filled('assessment_id')) {
            $selectedAssessment = Assessment::find($request->assessment_id);
        }

        return view('admin.assessment-verification.create', compact('assessments', 'verifiers', 'selectedAssessment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'assessment_unit_id' => 'nullable|exists:assessment_units,id',
            'verifier_id' => 'required|exists:users,id',
            'verification_level' => 'required|in:unit_level,assessment_level,quality_assurance',
            'verification_type' => 'required|in:internal,external,peer_review',
            'verification_status' => 'required|in:pending,in_progress,completed,approved,requires_modification,rejected',
            'verification_result' => 'nullable|in:satisfactory,needs_minor_changes,needs_major_changes,unsatisfactory',
            'findings' => 'nullable|string',
            'strengths' => 'nullable|string',
            'concerns' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'meets_standards' => 'nullable|boolean',
            'evidence_sufficient' => 'nullable|boolean',
            'assessment_fair' => 'nullable|boolean',
            'documentation_complete' => 'nullable|boolean',
            'verified_at' => 'nullable|date',
            'verification_duration_minutes' => 'nullable|integer|min:1',
            'verifier_notes' => 'nullable|string',
            'assessor_response' => 'nullable|string',
        ]);

        // Generate verification number
        $year = date('Y');
        $lastVerification = AssessmentVerification::whereYear('created_at', $year)
            ->orderBy('verification_number', 'desc')
            ->first();

        if ($lastVerification && preg_match('/VER-' . $year . '-(\d+)/', $lastVerification->verification_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $validated['verification_number'] = 'VER-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $verification = AssessmentVerification::create($validated);

        return redirect()->route('admin.assessment-verification.show', $verification)
            ->with('success', 'Verification berhasil dibuat');
    }

    public function show(AssessmentVerification $assessmentVerification)
    {
        $assessmentVerification->load([
            'assessment.assessee',
            'assessmentUnit.assessment.assessee',
            'verifier',
        ]);

        return view('admin.assessment-verification.show', compact('assessmentVerification'));
    }

    public function edit(AssessmentVerification $assessmentVerification)
    {
        $assessmentVerification->load(['assessment', 'assessmentUnit']);
        $verifiers = User::withRole('assessor')->orderBy('name')->get();

        return view('admin.assessment-verification.edit', compact('assessmentVerification', 'verifiers'));
    }

    public function update(Request $request, AssessmentVerification $assessmentVerification)
    {
        $validated = $request->validate([
            'verifier_id' => 'required|exists:users,id',
            'verification_level' => 'required|in:unit_level,assessment_level,quality_assurance',
            'verification_type' => 'required|in:internal,external,peer_review',
            'verification_status' => 'required|in:pending,in_progress,completed,approved,requires_modification,rejected',
            'verification_result' => 'nullable|in:satisfactory,needs_minor_changes,needs_major_changes,unsatisfactory',
            'findings' => 'nullable|string',
            'strengths' => 'nullable|string',
            'concerns' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'meets_standards' => 'nullable|boolean',
            'evidence_sufficient' => 'nullable|boolean',
            'assessment_fair' => 'nullable|boolean',
            'documentation_complete' => 'nullable|boolean',
            'verified_at' => 'nullable|date',
            'verification_duration_minutes' => 'nullable|integer|min:1',
            'verifier_notes' => 'nullable|string',
            'assessor_response' => 'nullable|string',
        ]);

        $assessmentVerification->update($validated);

        return redirect()->route('admin.assessment-verification.show', $assessmentVerification)
            ->with('success', 'Verification berhasil diupdate');
    }

    public function destroy(AssessmentVerification $assessmentVerification)
    {
        $assessmentVerification->delete();

        return redirect()->route('admin.assessment-verification.index')
            ->with('success', 'Verification berhasil dihapus');
    }
}
