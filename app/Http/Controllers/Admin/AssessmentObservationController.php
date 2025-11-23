<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentObservation;
use App\Models\AssessmentUnit;
use App\Models\AssessmentCriteria;
use App\Models\User;
use Illuminate\Http\Request;

class AssessmentObservationController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessmentObservation::with(['assessmentUnit.assessment.assessee', 'observer'])
            ->orderBy('observed_at', 'desc');

        if ($request->filled('assessment_unit_id')) {
            $query->where('assessment_unit_id', $request->assessment_unit_id);
        }

        if ($request->filled('observer_id')) {
            $query->where('observer_id', $request->observer_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('observation_number', 'like', "%{$search}%")
                  ->orWhere('activity_observed', 'like', "%{$search}%");
            });
        }

        $observations = $query->paginate(15);

        return view('admin.assessment-observations.index', compact('observations'));
    }

    public function create(Request $request)
    {
        $units = AssessmentUnit::with('assessment.assessee')->orderBy('created_at', 'desc')->limit(50)->get();
        $observers = User::withRole('assessor')->orderBy('name')->get();

        $selectedUnit = null;
        if ($request->filled('assessment_unit_id')) {
            $selectedUnit = AssessmentUnit::find($request->assessment_unit_id);
        }

        return view('admin.assessment-observations.create', compact('units', 'observers', 'selectedUnit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_unit_id' => 'required|exists:assessment_units,id',
            'assessment_criteria_id' => 'nullable|exists:assessment_criteria,id',
            'observer_id' => 'required|exists:users,id',
            'activity_observed' => 'required|string|max:255',
            'context' => 'nullable|string',
            'observed_at' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'what_was_observed' => 'nullable|string',
            'performance_indicators' => 'nullable|string',
            'evidence_collected' => 'nullable|string',
            'competency_demonstrated' => 'nullable|in:yes,no,partial',
            'score' => 'nullable|numeric|min:0|max:100',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'observer_notes' => 'nullable|string',
            'requires_follow_up' => 'boolean',
            'follow_up_notes' => 'nullable|string',
        ]);

        // Generate observation number
        $year = date('Y');
        $lastObservation = AssessmentObservation::where('observation_number', 'like', "OBS-{$year}-%")
            ->orderBy('observation_number', 'desc')
            ->first();

        if ($lastObservation) {
            $lastNumber = intval(substr($lastObservation->observation_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $validated['observation_number'] = "OBS-{$year}-{$newNumber}";

        $observation = AssessmentObservation::create($validated);

        return redirect()->route('admin.assessment-observations.show', $observation)
            ->with('success', 'Observation berhasil dibuat');
    }

    public function show(AssessmentObservation $assessmentObservation)
    {
        $assessmentObservation->load([
            'assessmentUnit.assessment.assessee',
            'assessmentCriteria',
            'observer',
        ]);

        return view('admin.assessment-observations.show', compact('assessmentObservation'));
    }

    public function edit(AssessmentObservation $assessmentObservation)
    {
        $assessmentObservation->load(['assessmentUnit', 'assessmentCriteria']);
        $observers = User::withRole('assessor')->orderBy('name')->get();

        return view('admin.assessment-observations.edit', compact('assessmentObservation', 'observers'));
    }

    public function update(Request $request, AssessmentObservation $assessmentObservation)
    {
        $validated = $request->validate([
            'observer_id' => 'required|exists:users,id',
            'activity_observed' => 'required|string|max:255',
            'context' => 'nullable|string',
            'observed_at' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'what_was_observed' => 'nullable|string',
            'performance_indicators' => 'nullable|string',
            'evidence_collected' => 'nullable|string',
            'competency_demonstrated' => 'nullable|in:yes,no,partial',
            'score' => 'nullable|numeric|min:0|max:100',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'observer_notes' => 'nullable|string',
            'requires_follow_up' => 'boolean',
            'follow_up_notes' => 'nullable|string',
        ]);

        $assessmentObservation->update($validated);

        return redirect()->route('admin.assessment-observations.show', $assessmentObservation)
            ->with('success', 'Observation berhasil diupdate');
    }

    public function destroy(AssessmentObservation $assessmentObservation)
    {
        $assessmentObservation->delete();

        return redirect()->route('admin.assessment-observations.index')
            ->with('success', 'Observation berhasil dihapus');
    }
}
