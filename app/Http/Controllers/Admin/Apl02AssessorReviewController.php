<?php

namespace App\Http\Controllers\Admin;

use App\Events\Apl02AllUnitsCompetent;
use App\Http\Controllers\Controller;
use App\Models\Apl02AssessorReview;
use App\Models\Apl02Unit;
use App\Models\User;
use App\Services\AssessmentSchedulerService;
use Illuminate\Http\Request;

class Apl02AssessorReviewController extends Controller
{
    protected AssessmentSchedulerService $schedulerService;

    public function __construct(AssessmentSchedulerService $schedulerService)
    {
        $this->schedulerService = $schedulerService;
    }
    public function index(Request $request)
    {
        $query = Apl02AssessorReview::with(['apl02Unit.assessee', 'assessor']);

        // Filters
        if ($request->filled('review_type')) {
            $query->where('review_type', $request->review_type);
        }

        if ($request->filled('decision')) {
            $query->where('decision', $request->decision);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assessor_id')) {
            $query->where('assessor_id', $request->assessor_id);
        }

        if ($request->filled('is_final')) {
            $query->where('is_final', $request->is_final === '1');
        }

        if ($request->filled('search')) {
            $query->where('review_number', 'like', '%' . $request->search . '%');
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reviews = $query->paginate(20);

        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'Assessor');
        })->orderBy('name')->get();

        return view('admin.apl02.reviews.index', compact('reviews', 'assessors'));
    }

    public function create(Request $request)
    {
        $unitId = $request->get('unit_id');
        $unit = $unitId ? Apl02Unit::with(['assessee', 'schemeUnit.elements', 'evidence'])->findOrFail($unitId) : null;
        $units = Apl02Unit::with('assessee')->orderBy('unit_code')->get();
        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'Assessor');
        })->orderBy('name')->get();

        return view('admin.apl02.reviews.create', compact('unit', 'units', 'assessors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'apl02_unit_id' => 'required|exists:apl02_units,id',
            'assessor_id' => 'required|exists:users,id',
            'review_type' => 'required|in:initial_review,verification,validation,final_assessment,re_assessment',
            'deadline' => 'nullable|date',
        ]);

        $validated['status'] = 'draft';

        $review = Apl02AssessorReview::create($validated);

        return redirect()
            ->route('admin.apl02.reviews.show', $review)
            ->with('success', 'Review created successfully.');
    }

    public function show(Apl02AssessorReview $review)
    {
        $review->load([
            'apl02Unit.assessee',
            'apl02Unit.schemeUnit.elements',
            'apl02Unit.evidence.evidenceMaps.schemeElement',
            'assessor',
            'verifiedBy'
        ]);

        return view('admin.apl02.reviews.show', compact('review'));
    }

    public function edit(Apl02AssessorReview $review)
    {
        $units = Apl02Unit::with('assessee')->orderBy('unit_code')->get();
        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'Assessor');
        })->orderBy('name')->get();

        return view('admin.apl02.reviews.edit', compact('review', 'units', 'assessors'));
    }

    public function update(Request $request, Apl02AssessorReview $review)
    {
        $validated = $request->validate([
            'review_type' => 'required|in:initial_review,verification,validation,final_assessment,re_assessment',
            'status' => 'required|in:draft,in_progress,completed,submitted,approved,revision_required',
            'decision' => 'required|in:pending,competent,not_yet_competent,requires_more_evidence,requires_demonstration,deferred',
            'overall_comments' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        // Set completed_at timestamp when status changes to completed
        if ($validated['status'] === 'completed' && $review->status !== 'completed') {
            $validated['review_completed_at'] = now();
        }

        $review->update($validated);

        return redirect()
            ->route('admin.apl02.reviews.show', $review)
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Apl02AssessorReview $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.apl02.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    // Conduct review
    public function conduct(Apl02AssessorReview $review)
    {
        $review->load([
            'apl02Unit.assessee',
            'apl02Unit.schemeUnit.elements',
            'apl02Unit.evidence.evidenceMaps.schemeElement',
            'apl02Unit.evidence.verifiedBy'
        ]);

        // Get available elements for mapping
        $availableElements = $review->apl02Unit->schemeUnit->elements ?? collect();

        return view('admin.apl02.reviews.conduct', compact('review', 'availableElements'));
    }

    public function submitReview(Request $request, Apl02AssessorReview $review)
    {
        $validated = $request->validate([
            'decision' => 'required|in:competent,not_yet_competent,pending,requires_more_evidence,requires_demonstration,deferred',
            'validity_score' => 'nullable|numeric|min:0|max:100',
            'authenticity_score' => 'nullable|numeric|min:0|max:100',
            'currency_score' => 'nullable|numeric|min:0|max:100',
            'sufficiency_score' => 'nullable|numeric|min:0|max:100',
            'consistency_score' => 'nullable|numeric|min:0|max:100',
            'overall_comments' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'strengths' => 'nullable|array',
            'weaknesses' => 'nullable|array',
            'improvement_areas' => 'nullable|array',
            'next_steps' => 'nullable|array',
            'requires_interview' => 'boolean',
            'requires_demonstration' => 'boolean',
            'interview_notes' => 'nullable|string',
            'demonstration_notes' => 'nullable|string',
        ]);

        // Start review if not started
        if ($review->status === 'draft') {
            $review->startReview();
        }

        $scores = [
            'validity' => $request->validity_score,
            'authenticity' => $request->authenticity_score,
            'currency' => $request->currency_score,
            'sufficiency' => $request->sufficiency_score,
            'consistency' => $request->consistency_score,
        ];

        // Complete review
        $review->completeReview($request->decision, $scores);

        // Update other fields
        $review->update([
            'overall_comments' => $request->overall_comments,
            'recommendations' => $request->recommendations,
            'strengths' => $request->strengths,
            'weaknesses' => $request->weaknesses,
            'improvement_areas' => $request->improvement_areas,
            'next_steps' => $request->next_steps,
            'requires_interview' => $request->has('requires_interview'),
            'requires_demonstration' => $request->has('requires_demonstration'),
            'interview_notes' => $request->interview_notes,
            'demonstration_notes' => $request->demonstration_notes,
        ]);

        // Update the APL-02 unit assessment result
        $unit = $review->apl02Unit;
        if ($unit && $request->decision === 'competent') {
            $unit->assessment_result = 'competent';
            $unit->status = 'competent';
            $unit->completed_at = now();
            $unit->save();

            // Check if all units are complete and dispatch event
            $apl01 = $unit->apl01Form;
            if ($apl01 && $this->schedulerService->areAllApl02UnitsComplete($apl01)) {
                event(new Apl02AllUnitsCompetent($apl01, $unit));
            }
        } elseif ($unit && $request->decision === 'not_yet_competent') {
            $unit->assessment_result = 'not_yet_competent';
            $unit->status = 'not_yet_competent';
            $unit->save();
        }

        return redirect()
            ->route('admin.apl02.reviews.show', $review)
            ->with('success', 'Review submitted successfully.');
    }

    public function verify(Request $request, Apl02AssessorReview $review)
    {
        $request->validate([
            'verification_notes' => 'nullable|string',
        ]);

        if ($review->verify(auth()->id(), $request->verification_notes)) {
            return redirect()
                ->route('admin.apl02.reviews.show', $review)
                ->with('success', 'Review verified successfully.');
        }

        return back()->with('error', 'Unable to verify review.');
    }

    public function requireRevision(Request $request, Apl02AssessorReview $review)
    {
        $request->validate([
            'verification_notes' => 'required|string',
        ]);

        if ($review->requireRevision($request->verification_notes)) {
            return redirect()
                ->route('admin.apl02.reviews.show', $review)
                ->with('success', 'Revision requested successfully.');
        }

        return back()->with('error', 'Unable to request revision.');
    }

    public function markAsFinal(Apl02AssessorReview $review)
    {
        if ($review->markAsFinal()) {
            return redirect()
                ->route('admin.apl02.reviews.show', $review)
                ->with('success', 'Review marked as final.');
        }

        return back()->with('error', 'Unable to mark as final.');
    }

    public function scheduleReAssessment(Request $request, Apl02AssessorReview $review)
    {
        $request->validate([
            're_assessment_date' => 'required|date|after:today',
            'recommendations' => 'nullable|string',
        ]);

        if ($review->scheduleReAssessment($request->re_assessment_date, $request->recommendations)) {
            return redirect()
                ->route('admin.apl02.reviews.show', $review)
                ->with('success', 'Re-assessment scheduled successfully.');
        }

        return back()->with('error', 'Unable to schedule re-assessment.');
    }

    // My reviews (for current assessor)
    public function myReviews(Request $request)
    {
        $query = Apl02AssessorReview::with(['apl02Unit.assessee', 'assessor'])
            ->where('assessor_id', auth()->id());

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('decision')) {
            $query->where('decision', $request->decision);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.apl02.reviews.my-reviews', compact('reviews'));
    }
}
