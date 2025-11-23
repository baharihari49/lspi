<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentUnit;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Http\Request;

class AssessmentUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AssessmentUnit::with(['assessment.assessee', 'assessment.scheme', 'assessor'])
            ->orderBy('created_at', 'desc');

        // Filter by assessment
        if ($request->filled('assessment_id')) {
            $query->where('assessment_id', $request->assessment_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by result
        if ($request->filled('result')) {
            $query->where('result', $request->result);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('unit_code', 'like', "%{$search}%")
                  ->orWhere('unit_title', 'like', "%{$search}%");
            });
        }

        $units = $query->paginate(15);

        return view('admin.assessment-units.index', compact('units'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AssessmentUnit $assessmentUnit)
    {
        $assessmentUnit->load([
            'assessment.assessee',
            'assessment.scheme',
            'assessor',
            'criteria',
            'observations',
            'interviews',
            'feedback'
        ]);

        return view('admin.assessment-units.show', compact('assessmentUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssessmentUnit $assessmentUnit)
    {
        $assessmentUnit->load(['assessment', 'criteria']);
        $assessors = User::withRole('assessor')->orderBy('name')->get();

        return view('admin.assessment-units.edit', compact('assessmentUnit', 'assessors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssessmentUnit $assessmentUnit)
    {
        $validated = $request->validate([
            'assessor_id' => 'nullable|exists:users,id',
            'assessment_method' => 'required|in:portfolio,observation,interview,demonstration,written_test,mixed',
            'status' => 'required|in:pending,in_progress,completed',
            'result' => 'required|in:pending,competent,not_yet_competent',
            'score' => 'nullable|numeric|min:0|max:100',
            'assessor_notes' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        // Calculate score based on criteria if not provided
        if (!isset($validated['score'])) {
            $criteria = $assessmentUnit->criteria;
            if ($criteria->count() > 0) {
                $competentCount = $criteria->where('result', 'competent')->count();
                $validated['score'] = ($competentCount / $criteria->count()) * 100;
            }
        }

        $assessmentUnit->update($validated);

        return redirect()->route('admin.assessment-units.show', $assessmentUnit)
            ->with('success', 'Assessment unit berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssessmentUnit $assessmentUnit)
    {
        // Only allow deletion if status is pending
        if ($assessmentUnit->status !== 'pending') {
            return redirect()->route('admin.assessment-units.index')
                ->with('error', 'Hanya unit dengan status pending yang dapat dihapus');
        }

        $assessmentUnit->delete();

        return redirect()->route('admin.assessment-units.index')
            ->with('success', 'Assessment unit berhasil dihapus');
    }

    /**
     * Start assessment for a unit
     */
    public function start(AssessmentUnit $assessmentUnit)
    {
        if ($assessmentUnit->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Unit sudah dimulai atau selesai');
        }

        $assessmentUnit->update([
            'status' => 'in_progress',
            'started_at' => now(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Assessment unit telah dimulai');
    }

    /**
     * Complete assessment for a unit
     */
    public function complete(AssessmentUnit $assessmentUnit)
    {
        if ($assessmentUnit->status === 'completed') {
            return redirect()->back()
                ->with('error', 'Unit sudah selesai');
        }

        // Calculate final score and result
        $criteria = $assessmentUnit->criteria;
        $totalCriteria = $criteria->count();

        if ($totalCriteria === 0) {
            return redirect()->back()
                ->with('error', 'Tidak ada criteria untuk unit ini');
        }

        $competentCount = $criteria->where('result', 'competent')->count();
        $score = ($competentCount / $totalCriteria) * 100;

        // Check critical criteria
        $criticalCriteria = $criteria->where('is_critical', true);
        $allCriticalMet = $criticalCriteria->count() === 0 ||
                         $criticalCriteria->where('result', 'competent')->count() === $criticalCriteria->count();

        // Determine result
        $result = 'not_yet_competent';
        if ($allCriticalMet && $score >= 70) { // Assuming 70% is passing grade
            $result = 'competent';
        }

        $assessmentUnit->update([
            'status' => 'completed',
            'result' => $result,
            'score' => $score,
            'completed_at' => now(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', "Assessment unit selesai dengan hasil: " . ucwords(str_replace('_', ' ', $result)));
    }
}
