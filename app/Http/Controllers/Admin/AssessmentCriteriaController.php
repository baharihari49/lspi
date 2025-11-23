<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentCriteria;
use App\Models\AssessmentUnit;
use Illuminate\Http\Request;

class AssessmentCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AssessmentCriteria::with(['assessmentUnit.assessment.assessee'])
            ->orderBy('created_at', 'desc');

        // Filter by assessment unit
        if ($request->filled('assessment_unit_id')) {
            $query->where('assessment_unit_id', $request->assessment_unit_id);
        }

        // Filter by result
        if ($request->filled('result')) {
            $query->where('result', $request->result);
        }

        // Filter by critical
        if ($request->filled('is_critical')) {
            $query->where('is_critical', $request->is_critical === 'true');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('element_code', 'like', "%{$search}%")
                  ->orWhere('element_title', 'like', "%{$search}%");
            });
        }

        $criteria = $query->paginate(15);

        return view('admin.assessment-criteria.index', compact('criteria'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AssessmentCriteria $assessmentCriterion)
    {
        $assessmentCriterion->load([
            'assessmentUnit.assessment.assessee',
            'assessmentUnit.assessor',
        ]);

        return view('admin.assessment-criteria.show', compact('assessmentCriterion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssessmentCriteria $assessmentCriterion)
    {
        $assessmentCriterion->load(['assessmentUnit']);

        return view('admin.assessment-criteria.edit', compact('assessmentCriterion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssessmentCriteria $assessmentCriterion)
    {
        $validated = $request->validate([
            'result' => 'required|in:pending,competent,not_yet_competent',
            'assessment_method' => 'required|in:portfolio,observation,interview,demonstration,written_test,mixed',
            'evidence_collected' => 'nullable|string',
            'notes' => 'nullable|string',
            'assessor_feedback' => 'nullable|string',
            'is_critical' => 'boolean',
        ]);

        $validated['assessed_at'] = now();
        $validated['assessed_by'] = auth()->id();

        $assessmentCriterion->update($validated);

        return redirect()->route('admin.assessment-criteria.show', $assessmentCriterion)
            ->with('success', 'Assessment criteria berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssessmentCriteria $assessmentCriterion)
    {
        // Only allow deletion if not yet assessed
        if ($assessmentCriterion->result !== 'pending') {
            return redirect()->route('admin.assessment-criteria.index')
                ->with('error', 'Hanya criteria dengan status pending yang dapat dihapus');
        }

        $assessmentCriterion->delete();

        return redirect()->route('admin.assessment-criteria.index')
            ->with('success', 'Assessment criteria berhasil dihapus');
    }

    /**
     * Assess a single criterion
     */
    public function assess(Request $request, AssessmentCriteria $assessmentCriterion)
    {
        $validated = $request->validate([
            'result' => 'required|in:competent,not_yet_competent',
            'evidence_collected' => 'nullable|string',
            'notes' => 'nullable|string',
            'assessor_feedback' => 'nullable|string',
        ]);

        $validated['assessed_at'] = now();
        $validated['assessed_by'] = auth()->id();

        $assessmentCriterion->update($validated);

        // Update assessment unit score if all criteria assessed
        $this->updateUnitScore($assessmentCriterion->assessmentUnit);

        return redirect()->back()
            ->with('success', 'Criteria berhasil di-assess: ' . ucwords(str_replace('_', ' ', $validated['result'])));
    }

    /**
     * Bulk assess multiple criteria
     */
    public function bulkAssess(Request $request)
    {
        $validated = $request->validate([
            'criteria_ids' => 'required|array|min:1',
            'criteria_ids.*' => 'exists:assessment_criteria,id',
            'result' => 'required|in:competent,not_yet_competent',
            'notes' => 'nullable|string',
        ]);

        $updated = 0;
        $assessmentUnits = collect();

        foreach ($validated['criteria_ids'] as $criteriaId) {
            $criterion = AssessmentCriteria::find($criteriaId);

            if ($criterion && $criterion->result === 'pending') {
                $criterion->update([
                    'result' => $validated['result'],
                    'notes' => $validated['notes'] ?? $criterion->notes,
                    'assessed_at' => now(),
                    'assessed_by' => auth()->id(),
                ]);

                $updated++;

                // Collect unique assessment units for score update
                if (!$assessmentUnits->contains($criterion->assessment_unit_id)) {
                    $assessmentUnits->push($criterion->assessmentUnit);
                }
            }
        }

        // Update scores for all affected assessment units
        foreach ($assessmentUnits as $unit) {
            $this->updateUnitScore($unit);
        }

        return redirect()->back()
            ->with('success', "{$updated} criteria berhasil di-assess");
    }

    /**
     * Mark criterion as critical
     */
    public function toggleCritical(AssessmentCriteria $assessmentCriterion)
    {
        $assessmentCriterion->update([
            'is_critical' => !$assessmentCriterion->is_critical,
        ]);

        $status = $assessmentCriterion->is_critical ? 'marked as critical' : 'unmarked as critical';

        return redirect()->back()
            ->with('success', "Criteria berhasil {$status}");
    }

    /**
     * Private helper to update assessment unit score
     */
    private function updateUnitScore(AssessmentUnit $unit)
    {
        $criteria = $unit->criteria;
        $totalCriteria = $criteria->count();

        if ($totalCriteria === 0) {
            return;
        }

        $competentCount = $criteria->where('result', 'competent')->count();
        $score = ($competentCount / $totalCriteria) * 100;

        $unit->update([
            'score' => $score,
            'updated_by' => auth()->id(),
        ]);
    }

    /**
     * Show assessment form for criteria within a unit
     */
    public function assessForm(AssessmentUnit $assessmentUnit)
    {
        $assessmentUnit->load(['criteria', 'assessment.assessee']);

        return view('admin.assessment-criteria.assess-form', compact('assessmentUnit'));
    }
}
