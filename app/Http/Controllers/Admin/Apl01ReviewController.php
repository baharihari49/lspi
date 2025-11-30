<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl01Review;
use App\Models\Apl01Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Apl01ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Apl01Review::with(['form.assessee', 'form.scheme', 'reviewer'])
            ->latest();

        // Filter by decision
        if ($request->filled('decision')) {
            $query->where('decision', $request->decision);
        }

        // Filter by reviewer
        if ($request->filled('reviewer_id')) {
            $query->where('reviewer_id', $request->reviewer_id);
        }

        // Filter by review level
        if ($request->filled('review_level')) {
            $query->where('review_level', $request->review_level);
        }

        // Filter by form
        if ($request->filled('apl01_form_id')) {
            $query->where('apl01_form_id', $request->apl01_form_id);
        }

        // Filter current reviews only
        if ($request->filled('current_only') && $request->current_only) {
            $query->where('is_current', true);
        }

        // Filter overdue
        if ($request->filled('overdue') && $request->overdue) {
            $query->where('deadline', '<', now())
                ->where('decision', 'pending');
        }

        $reviews = $query->paginate(20);
        $reviewers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'reviewer']);
        })->get();

        // Get pending submissions (forms with status 'submitted' but no review record)
        $pendingSubmissions = Apl01Form::with(['assessee', 'scheme', 'event'])
            ->where('status', 'submitted')
            ->doesntHave('reviews')
            ->latest()
            ->get();

        return view('admin.apl01.reviews.index', compact('reviews', 'reviewers', 'pendingSubmissions'));
    }

    /**
     * Show review detail
     */
    public function show(Apl01Review $review)
    {
        $review->load([
            'form.assessee',
            'form.scheme',
            'form.answers.formField',
            'reviewer',
            'escalatedTo',
        ]);

        return view('admin.apl01.reviews.show', compact('review'));
    }

    /**
     * Show review form (for reviewer to perform review)
     */
    public function review(Apl01Review $review)
    {
        // Check if current user is the assigned reviewer
        if ((int)$review->reviewer_id !== (int)auth()->id() && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'You are not authorized to review this form.');
        }

        // Check if review is still pending
        if ($review->decision !== 'pending') {
            return back()->with('error', 'This review has already been completed.');
        }

        $review->load([
            'form.assessee',
            'form.scheme.formFields' => function ($query) {
                $query->active()
                    ->visible()
                    ->orderBy('section')
                    ->orderBy('order');
            },
            'form.answers.formField',
        ]);

        return view('admin.apl01.reviews.review', compact('review'));
    }

    /**
     * Start review
     */
    public function start(Apl01Review $review)
    {
        if ((int)$review->reviewer_id !== (int)auth()->id() && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'You are not authorized to review this form.');
        }

        if ($review->decision !== 'pending') {
            return back()->with('error', 'This review has already been completed.');
        }

        DB::beginTransaction();
        try {
            $review->startReview(auth()->id());
            DB::commit();

            return redirect()
                ->route('admin.apl01-reviews.review', $review)
                ->with('success', 'Review started.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to start review: ' . $e->getMessage());
        }
    }

    /**
     * Submit review decision
     */
    public function submitDecision(Request $request, Apl01Review $review)
    {
        if ((int)$review->reviewer_id !== (int)auth()->id() && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'You are not authorized to review this form.');
        }

        if ($review->decision !== 'pending') {
            return back()->with('error', 'This review has already been completed.');
        }

        $validated = $request->validate([
            'decision' => 'required|in:approved,rejected,approved_with_notes,returned,forwarded,on_hold',
            'review_notes' => 'required|string',
            'checklist_items' => 'nullable|array',
            'field_feedback' => 'nullable|array',
            'score' => 'nullable|integer|min:0|max:100',
            'attachments' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            if ($validated['decision'] === 'approved' || $validated['decision'] === 'approved_with_notes') {
                $review->approve($validated['review_notes'], auth()->id());
            } elseif ($validated['decision'] === 'rejected') {
                $review->reject($validated['review_notes'], auth()->id());
            } else {
                // For other decisions, just update the review
                $review->decision = $validated['decision'];
                $review->review_notes = $validated['review_notes'];
                $review->completed_at = now();
                $review->save();
            }

            // Update additional fields
            if (isset($validated['checklist_items'])) {
                $review->checklist_items = $validated['checklist_items'];
            }
            if (isset($validated['field_feedback'])) {
                $review->field_feedback = $validated['field_feedback'];
            }
            if (isset($validated['score'])) {
                $review->score = $validated['score'];
            }
            if (isset($validated['attachments'])) {
                $review->attachments = $validated['attachments'];
            }
            $review->save();

            DB::commit();

            return redirect()
                ->route('admin.apl01.show', $review->form)
                ->with('success', 'Review submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit review: ' . $e->getMessage());
        }
    }

    /**
     * Approve review
     */
    public function approve(Request $request, Apl01Review $review)
    {
        if ((int)$review->reviewer_id !== (int)auth()->id() && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'You are not authorized to review this form.');
        }

        $request->validate([
            'review_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $review->approve($request->review_notes, auth()->id());
            DB::commit();

            return redirect()
                ->route('admin.apl01.show', $review->form)
                ->with('success', 'Form approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve form: ' . $e->getMessage());
        }
    }

    /**
     * Reject review
     */
    public function reject(Request $request, Apl01Review $review)
    {
        if ((int)$review->reviewer_id !== (int)auth()->id() && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'You are not authorized to review this form.');
        }

        $request->validate([
            'review_notes' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $review->reject($request->review_notes, auth()->id());
            DB::commit();

            return redirect()
                ->route('admin.apl01.show', $review->form)
                ->with('success', 'Form rejected.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject form: ' . $e->getMessage());
        }
    }

    /**
     * Escalate review
     */
    public function escalate(Request $request, Apl01Review $review)
    {
        if ((int)$review->reviewer_id !== (int)auth()->id() && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'You are not authorized to escalate this review.');
        }

        $request->validate([
            'escalation_reason' => 'required|string',
            'escalated_to' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $review->escalate(
                $request->escalation_reason,
                $request->escalated_to,
                auth()->id()
            );

            // Optionally reassign the review
            $review->reviewer_id = $request->escalated_to;
            $review->save();

            DB::commit();

            return redirect()
                ->route('admin.apl01-reviews.show', $review)
                ->with('success', 'Review escalated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to escalate review: ' . $e->getMessage());
        }
    }

    /**
     * Reassign review to another reviewer
     */
    public function reassign(Request $request, Apl01Review $review)
    {
        if (!auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Only admins can reassign reviews.');
        }

        $request->validate([
            'reviewer_id' => 'required|exists:users,id',
            'reason' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $review->reviewer_id = $request->reviewer_id;
            $review->assigned_at = now();

            if ($request->filled('reason')) {
                $review->review_notes = ($review->review_notes ?? '') . "\n\nReassignment reason: " . $request->reason;
            }

            $review->save();

            DB::commit();

            return back()->with('success', 'Review reassigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reassign review: ' . $e->getMessage());
        }
    }

    /**
     * Update review deadline
     */
    public function updateDeadline(Request $request, Apl01Review $review)
    {
        if (!auth()->user()->hasRole('admin') && (int)$review->reviewer_id !== (int)auth()->id()) {
            return back()->with('error', 'You are not authorized to update this deadline.');
        }

        $request->validate([
            'deadline' => 'required|date|after:now',
        ]);

        DB::beginTransaction();
        try {
            $review->deadline = $request->deadline;
            $review->save();

            DB::commit();

            return back()->with('success', 'Deadline updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update deadline: ' . $e->getMessage());
        }
    }

    /**
     * Get my pending reviews
     */
    public function myReviews(Request $request)
    {
        $query = Apl01Review::with(['form.assessee', 'form.scheme'])
            ->where('reviewer_id', auth()->id())
            ->latest();

        // Filter by status
        $status = $request->get('status', 'pending');
        if ($status === 'pending') {
            $query->where('decision', 'pending');
        } elseif ($status === 'completed') {
            $query->where('decision', '!=', 'pending');
        }

        $reviews = $query->paginate(20);

        return view('admin.apl01.reviews.my-reviews', compact('reviews', 'status'));
    }
}
