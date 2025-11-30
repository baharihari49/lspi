<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl02Form;
use App\Models\Apl01Form;
use App\Models\Assessee;
use App\Models\Scheme;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class Apl02FormController extends Controller
{
    public function index(Request $request)
    {
        $query = Apl02Form::with(['assessee', 'scheme', 'event', 'assessor', 'apl01Form']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assessment_result')) {
            $query->where('assessment_result', $request->assessment_result);
        }

        if ($request->filled('assessee_id')) {
            $query->where('assessee_id', $request->assessee_id);
        }

        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        if ($request->filled('assessor_id')) {
            $query->where('assessor_id', $request->assessor_id);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('form_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('assessee', function($aq) use ($request) {
                      $aq->where('full_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $forms = $query->paginate(20);

        // For filters
        $assessees = Assessee::orderBy('full_name')->get();
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'assessor');
        })->orderBy('name')->get();
        $events = Event::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.apl02-forms.index', compact('forms', 'assessees', 'schemes', 'assessors', 'events'));
    }

    public function show(Apl02Form $apl02Form)
    {
        $apl02Form->load([
            'assessee',
            'scheme.units',
            'event',
            'assessor',
            'apl01Form',
            'submittedBy',
            'completedBy',
        ]);

        return view('admin.apl02-forms.show', compact('apl02Form'));
    }

    public function edit(Apl02Form $apl02Form)
    {
        $apl02Form->load([
            'assessee',
            'scheme.units',
            'event',
            'assessor',
            'apl01Form',
        ]);

        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'assessor');
        })->orderBy('name')->get();

        return view('admin.apl02-forms.edit', compact('apl02Form', 'assessors'));
    }

    public function update(Request $request, Apl02Form $apl02Form)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,submitted,under_review,revision_required,approved,rejected,completed',
            'assessor_id' => 'nullable|exists:users,id',
            'assessment_result' => 'nullable|in:pending,competent,not_yet_competent',
            'assessor_notes' => 'nullable|string',
            'assessor_feedback' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $apl02Form->update($validated);

        return redirect()
            ->route('admin.apl02-forms.show', $apl02Form)
            ->with('success', 'APL-02 Form updated successfully.');
    }

    public function destroy(Apl02Form $apl02Form)
    {
        $apl02Form->delete();

        return redirect()
            ->route('admin.apl02-forms.index')
            ->with('success', 'APL-02 Form deleted successfully.');
    }

    // Additional Actions
    public function assignAssessor(Request $request, Apl02Form $apl02Form)
    {
        $request->validate([
            'assessor_id' => 'required|exists:users,id',
        ]);

        $apl02Form->assignAssessor($request->assessor_id);

        return redirect()
            ->route('admin.apl02-forms.show', $apl02Form)
            ->with('success', 'Assessor assigned successfully.');
    }

    public function startReview(Apl02Form $apl02Form)
    {
        $apl02Form->startReview();

        return redirect()
            ->route('admin.apl02-forms.show', $apl02Form)
            ->with('success', 'Review started successfully.');
    }

    public function approve(Request $request, Apl02Form $apl02Form)
    {
        $request->validate([
            'assessor_notes' => 'nullable|string',
            'assessor_feedback' => 'nullable|string',
        ]);

        $apl02Form->approve($request->assessor_notes, $request->assessor_feedback);

        return redirect()
            ->route('admin.apl02-forms.show', $apl02Form)
            ->with('success', 'APL-02 Form approved successfully.');
    }

    public function reject(Request $request, Apl02Form $apl02Form)
    {
        $request->validate([
            'assessor_notes' => 'required|string',
            'assessor_feedback' => 'nullable|string',
        ]);

        $apl02Form->reject($request->assessor_notes, $request->assessor_feedback);

        return redirect()
            ->route('admin.apl02-forms.show', $apl02Form)
            ->with('success', 'APL-02 Form rejected.');
    }

    public function requestRevision(Request $request, Apl02Form $apl02Form)
    {
        $request->validate([
            'revision_notes' => 'required|array',
            'revision_notes.*' => 'string',
        ]);

        $apl02Form->requestRevision($request->revision_notes);

        return redirect()
            ->route('admin.apl02-forms.show', $apl02Form)
            ->with('success', 'Revision requested successfully.');
    }
}
