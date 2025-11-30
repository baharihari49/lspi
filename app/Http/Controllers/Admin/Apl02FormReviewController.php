<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl02Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Apl02FormReviewController extends Controller
{
    /**
     * Display listing of APL-02 Forms for review
     * Shows: unassigned submitted forms + forms assigned to current assessor
     */
    public function myReviews(Request $request)
    {
        $userId = auth()->id();

        $query = Apl02Form::with(['apl01Form.assessee', 'scheme', 'event', 'assessor']);

        // Filter by status
        $status = $request->get('status', 'pending');
        if ($status === 'pending') {
            // Show unassigned submitted OR assigned to me (submitted/under_review)
            $query->where(function ($q) use ($userId) {
                $q->where(function ($sub) {
                    // Unassigned submitted forms
                    $sub->whereNull('assessor_id')
                        ->where('status', 'submitted');
                })->orWhere(function ($sub) use ($userId) {
                    // Assigned to me and pending
                    $sub->where('assessor_id', $userId)
                        ->whereIn('status', ['submitted', 'under_review']);
                });
            });
        } elseif ($status === 'completed') {
            // Only show my completed reviews
            $query->where('assessor_id', $userId)
                ->whereIn('status', ['approved', 'rejected']);
        } elseif ($status === 'revision') {
            // Only show my revision requests
            $query->where('assessor_id', $userId)
                ->where('status', 'revision_required');
        }

        $apl02Forms = $query->latest()->paginate(20);

        return view('admin.apl02-forms.my-reviews', compact('apl02Forms', 'status'));
    }

    /**
     * Show APL-02 Form for review
     */
    public function show(Apl02Form $apl02Form)
    {
        // Authorization: assigned to me, unassigned submitted, or admin
        $canAccess = $apl02Form->assessor_id === auth()->id()
            || ($apl02Form->assessor_id === null && $apl02Form->status === 'submitted')
            || auth()->user()->hasAnyRole(['admin', 'super-admin']);

        if (!$canAccess) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mereview form ini.');
        }

        $apl02Form->load([
            'apl01Form.assessee',
            'scheme.units',
            'event',
            'assessor',
        ]);

        return view('admin.apl02-forms.review', compact('apl02Form'));
    }

    /**
     * Claim/take an unassigned APL-02 form for review
     */
    public function claim(Apl02Form $apl02Form)
    {
        // Only allow claiming unassigned submitted forms
        if ($apl02Form->assessor_id !== null) {
            return back()->with('error', 'Form ini sudah di-assign ke assessor lain.');
        }

        if ($apl02Form->status !== 'submitted') {
            return back()->with('error', 'Form ini tidak dalam status yang dapat diklaim.');
        }

        DB::beginTransaction();
        try {
            $apl02Form->assignAssessor(auth()->id());

            DB::commit();

            return redirect()
                ->route('admin.apl02-form-reviews.show', $apl02Form)
                ->with('success', 'Form berhasil diklaim untuk direview.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengklaim form: ' . $e->getMessage());
        }
    }

    /**
     * Start review process
     */
    public function startReview(Apl02Form $apl02Form)
    {
        // Check authorization
        if ($apl02Form->assessor_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'super-admin'])) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mereview form ini.');
        }

        if (!in_array($apl02Form->status, ['submitted'])) {
            return back()->with('error', 'Form ini tidak dalam status yang dapat direview.');
        }

        DB::beginTransaction();
        try {
            $apl02Form->status = 'under_review';
            $apl02Form->startReview();

            DB::commit();

            return redirect()
                ->route('admin.apl02-form-reviews.review', $apl02Form)
                ->with('success', 'Review dimulai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memulai review: ' . $e->getMessage());
        }
    }

    /**
     * Show review form
     */
    public function review(Apl02Form $apl02Form)
    {
        // Check authorization
        if ($apl02Form->assessor_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'super-admin'])) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mereview form ini.');
        }

        if (!in_array($apl02Form->status, ['submitted', 'under_review'])) {
            return back()->with('error', 'Form ini tidak dalam status yang dapat direview.');
        }

        $apl02Form->load([
            'apl01Form.assessee',
            'scheme.units',
            'event',
            'assessor',
        ]);

        return view('admin.apl02-forms.review-form', compact('apl02Form'));
    }

    /**
     * Submit review decision - Approve
     */
    public function approve(Request $request, Apl02Form $apl02Form)
    {
        // Check authorization
        if ($apl02Form->assessor_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'super-admin'])) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mereview form ini.');
        }

        $request->validate([
            'assessor_notes' => 'nullable|string',
            'assessor_feedback' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $apl02Form->approve(
                $request->assessor_notes,
                $request->assessor_feedback
            );

            DB::commit();

            return redirect()
                ->route('admin.apl02-form-reviews.my-reviews')
                ->with('success', 'APL-02 Form berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui form: ' . $e->getMessage());
        }
    }

    /**
     * Submit review decision - Reject
     */
    public function reject(Request $request, Apl02Form $apl02Form)
    {
        // Check authorization
        if ($apl02Form->assessor_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'super-admin'])) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mereview form ini.');
        }

        $request->validate([
            'assessor_notes' => 'required|string',
            'assessor_feedback' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $apl02Form->reject(
                $request->assessor_notes,
                $request->assessor_feedback
            );

            DB::commit();

            return redirect()
                ->route('admin.apl02-form-reviews.my-reviews')
                ->with('success', 'APL-02 Form ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak form: ' . $e->getMessage());
        }
    }

    /**
     * Request revision
     */
    public function requestRevision(Request $request, Apl02Form $apl02Form)
    {
        // Check authorization
        if ($apl02Form->assessor_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'super-admin'])) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mereview form ini.');
        }

        $request->validate([
            'revision_notes' => 'required|array',
            'revision_notes.*' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $apl02Form->requestRevision($request->revision_notes);

            DB::commit();

            return redirect()
                ->route('admin.apl02-form-reviews.my-reviews')
                ->with('success', 'Permintaan revisi berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim permintaan revisi: ' . $e->getMessage());
        }
    }
}
