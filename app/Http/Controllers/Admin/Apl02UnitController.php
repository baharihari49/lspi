<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl02Unit;
use App\Models\Assessee;
use App\Models\Scheme;
use App\Models\SchemeUnit;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class Apl02UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Apl02Unit::with(['assessee', 'scheme', 'schemeUnit', 'event', 'assessor']);

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
                $q->where('unit_code', 'like', '%' . $request->search . '%')
                  ->orWhere('unit_title', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $units = $query->paginate(20);

        // For filters
        $assessees = Assessee::orderBy('full_name')->get();
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'Assessor');
        })->orderBy('name')->get();
        $events = Event::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.apl02.units.index', compact('units', 'assessees', 'schemes', 'assessors', 'events'));
    }

    public function create()
    {
        $assessees = Assessee::orderBy('full_name')->get();
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'Assessor');
        })->orderBy('name')->get();
        $events = Event::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.apl02.units.create', compact('assessees', 'schemes', 'assessors', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessee_id' => 'required|exists:assessees,id',
            'scheme_id' => 'required|exists:schemes,id',
            'scheme_unit_id' => 'required|exists:scheme_units,id',
            'event_id' => 'nullable|exists:events,id',
            'unit_code' => 'required|string|max:50',
            'unit_title' => 'required|string|max:255',
            'unit_description' => 'nullable|string',
            'status' => 'nullable|in:not_started,in_progress,submitted,under_review,competent,not_yet_competent,completed',
        ]);

        $validated['status'] = $validated['status'] ?? 'not_started';

        $unit = Apl02Unit::create($validated);

        return redirect()
            ->route('admin.apl02.units.show', $unit)
            ->with('success', 'APL-02 Unit created successfully.');
    }

    public function show(Apl02Unit $unit)
    {
        $unit->load([
            'assessee',
            'scheme',
            'schemeUnit.elements',
            'event',
            'assessor',
            'submittedBy',
            'evidence.evidenceMaps.schemeElement',
            'assessorReviews.assessor'
        ]);

        return view('admin.apl02.units.show', compact('unit'));
    }

    public function edit(Apl02Unit $unit)
    {
        $assessees = Assessee::orderBy('full_name')->get();
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        $currentVersion = $unit->scheme->currentVersion->first();
        $schemeUnits = $currentVersion
            ? SchemeUnit::where('scheme_version_id', $currentVersion->id)
                ->orderBy('order')
                ->get()
            : collect();
        $events = Event::where('is_active', true)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();
        $assessors = User::whereHas('roles', function($q) {
            $q->where('name', 'Assessor');
        })->orderBy('name')->get();

        return view('admin.apl02.units.edit', compact('unit', 'assessees', 'schemes', 'schemeUnits', 'events', 'assessors'));
    }

    public function update(Request $request, Apl02Unit $unit)
    {
        $validated = $request->validate([
            'assessee_id' => 'required|exists:assessees,id',
            'scheme_id' => 'required|exists:schemes,id',
            'scheme_unit_id' => 'required|exists:scheme_units,id',
            'event_id' => 'nullable|exists:events,id',
            'unit_code' => 'required|string|max:50',
            'unit_title' => 'required|string|max:255',
            'unit_description' => 'nullable|string',
            'status' => 'required|in:not_started,in_progress,submitted,under_review,competent,not_yet_competent,completed',
            'assessor_id' => 'nullable|exists:users,id',
            'assessment_notes' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()
            ->route('admin.apl02.units.show', $unit)
            ->with('success', 'APL-02 Unit updated successfully.');
    }

    public function destroy(Apl02Unit $unit)
    {
        $unit->delete();

        return redirect()
            ->route('admin.apl02.units.index')
            ->with('success', 'APL-02 Unit deleted successfully.');
    }

    // Additional Actions
    public function submit(Request $request, Apl02Unit $unit)
    {
        if ($unit->submit(auth()->id())) {
            return redirect()
                ->route('admin.apl02.units.show', $unit)
                ->with('success', 'APL-02 Unit submitted successfully.');
        }

        return back()->with('error', 'Unable to submit unit. It may be locked or already submitted.');
    }

    public function assignAssessor(Request $request, Apl02Unit $unit)
    {
        $request->validate([
            'assessor_id' => 'required|exists:users,id',
        ]);

        if ($unit->assignToAssessor($request->assessor_id)) {
            return redirect()
                ->route('admin.apl02.units.show', $unit)
                ->with('success', 'Assessor assigned successfully.');
        }

        return back()->with('error', 'Unable to assign assessor.');
    }

    public function startAssessment(Apl02Unit $unit)
    {
        if ($unit->startAssessment()) {
            return redirect()
                ->route('admin.apl02.units.show', $unit)
                ->with('success', 'Assessment started successfully.');
        }

        return back()->with('error', 'Unable to start assessment. Assessor must be assigned first.');
    }

    public function completeAssessment(Request $request, Apl02Unit $unit)
    {
        $request->validate([
            'assessment_result' => 'required|in:competent,not_yet_competent,requires_more_evidence',
            'score' => 'nullable|numeric|min:0|max:100',
            'assessment_notes' => 'nullable|string',
        ]);

        if ($unit->completeAssessment(
            $request->assessment_result,
            $request->score,
            $request->assessment_notes
        )) {
            return redirect()
                ->route('admin.apl02.units.show', $unit)
                ->with('success', 'Assessment completed successfully.');
        }

        return back()->with('error', 'Unable to complete assessment.');
    }

    public function lock(Apl02Unit $unit)
    {
        if ($unit->lock()) {
            return redirect()
                ->route('admin.apl02.units.show', $unit)
                ->with('success', 'Unit locked successfully.');
        }

        return back()->with('error', 'Unable to lock unit.');
    }

    public function unlock(Apl02Unit $unit)
    {
        if ($unit->unlock()) {
            return redirect()
                ->route('admin.apl02.units.show', $unit)
                ->with('success', 'Unit unlocked successfully.');
        }

        return back()->with('error', 'Unable to unlock unit.');
    }

    public function calculateCompletion(Apl02Unit $unit)
    {
        $unit->calculateCompletionPercentage();
        $unit->save();

        return redirect()
            ->route('admin.apl02.units.show', $unit)
            ->with('success', 'Completion percentage updated successfully.');
    }

    // Get scheme units by scheme
    public function getSchemeUnits(Request $request)
    {
        $schemeId = $request->get('scheme_id');

        $scheme = Scheme::find($schemeId);
        if (!$scheme || !$scheme->latestVersion) {
            return response()->json([]);
        }

        $units = SchemeUnit::where('scheme_version_id', $scheme->latestVersion->id)
            ->orderBy('order')
            ->get(['id', 'code', 'title']);

        return response()->json($units);
    }
}
