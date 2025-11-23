<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentResult;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AssessmentResult::with(['assessee', 'scheme', 'leadAssessor', 'assessment'])
            ->orderBy('created_at', 'desc');

        // Filter by result
        if ($request->filled('final_result')) {
            $query->where('final_result', $request->final_result);
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Filter by assessee
        if ($request->filled('assessee_id')) {
            $query->where('assessee_id', $request->assessee_id);
        }

        // Filter by scheme
        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        // Filter published
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('result_number', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%")
                  ->orWhereHas('assessee', function($q) use ($search) {
                      $q->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $results = $query->paginate(15);

        return view('admin.assessment-results.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get assessment from query parameter
        $assessmentId = $request->get('assessment_id');

        if (!$assessmentId) {
            return redirect()->route('admin.assessments.index')
                ->with('error', 'Assessment ID diperlukan');
        }

        $assessment = Assessment::with([
            'assessee',
            'scheme',
            'assessmentUnits.criteria'
        ])->findOrFail($assessmentId);

        // Check if assessment is completed
        if (!$assessment->isCompleted()) {
            return redirect()->route('admin.assessments.show', $assessment)
                ->with('error', 'Assessment harus diselesaikan terlebih dahulu');
        }

        // Check if result already exists
        if ($assessment->results()->exists()) {
            return redirect()->route('admin.assessment-results.show', $assessment->results->first())
                ->with('info', 'Result untuk assessment ini sudah ada');
        }

        // Calculate statistics
        $stats = $this->calculateAssessmentStats($assessment);

        return view('admin.assessment-results.create', compact('assessment', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'final_result' => 'required|in:competent,not_yet_competent,requires_reassessment',
            'executive_summary' => 'required|string',
            'key_strengths' => 'nullable|array',
            'development_areas' => 'nullable|array',
            'overall_performance_notes' => 'nullable|string',
            'recommendations' => 'nullable|array',
            'next_steps' => 'nullable|string',
            'reassessment_plan' => 'nullable|string',
            'certification_date' => 'nullable|date',
            'certification_expiry_date' => 'nullable|date',
            'certification_level' => 'nullable|string',
        ]);

        $assessment = Assessment::findOrFail($validated['assessment_id']);

        // Generate result number
        $year = date('Y');
        $lastResult = AssessmentResult::where('result_number', 'like', "RES-{$year}-%")
            ->orderBy('result_number', 'desc')
            ->first();

        if ($lastResult) {
            $lastNumber = intval(substr($lastResult->result_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $validated['result_number'] = "RES-{$year}-{$newNumber}";

        // Generate certificate number if competent
        if ($validated['final_result'] === 'competent') {
            $validated['certificate_number'] = "CERT-{$year}-{$newNumber}";
        }

        // Calculate statistics
        $stats = $this->calculateAssessmentStats($assessment);

        $validated['assessee_id'] = $assessment->assessee_id;
        $validated['scheme_id'] = $assessment->scheme_id;
        $validated['lead_assessor_id'] = $assessment->lead_assessor_id;
        $validated['overall_score'] = $stats['overall_score'];
        $validated['units_assessed'] = $stats['units_assessed'];
        $validated['units_competent'] = $stats['units_competent'];
        $validated['units_not_yet_competent'] = $stats['units_not_yet_competent'];
        $validated['total_criteria'] = $stats['total_criteria'];
        $validated['criteria_met'] = $stats['criteria_met'];
        $validated['criteria_percentage'] = $stats['criteria_percentage'];
        $validated['critical_criteria_total'] = $stats['critical_criteria_total'];
        $validated['critical_criteria_met'] = $stats['critical_criteria_met'];
        $validated['all_critical_criteria_met'] = $stats['all_critical_criteria_met'];
        $validated['unit_results'] = $stats['unit_results'];
        $validated['documents_submitted'] = $assessment->documents()->count();
        $validated['observations_conducted'] = DB::table('assessment_observations')
            ->join('assessment_units', 'assessment_observations.assessment_unit_id', '=', 'assessment_units.id')
            ->where('assessment_units.assessment_id', $assessment->id)
            ->count();
        $validated['interviews_conducted'] = DB::table('assessment_interviews')
            ->join('assessment_units', 'assessment_interviews.assessment_unit_id', '=', 'assessment_units.id')
            ->where('assessment_units.assessment_id', $assessment->id)
            ->count();
        $validated['approval_status'] = 'pending';
        $validated['is_published'] = false;
        $validated['is_valid'] = true;
        $validated['created_by'] = auth()->id();

        $result = AssessmentResult::create($validated);

        return redirect()->route('admin.assessment-results.show', $result)
            ->with('success', 'Result assessment berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(AssessmentResult $assessmentResult)
    {
        $assessmentResult->load([
            'assessment.assessmentUnits.criteria',
            'assessee',
            'scheme',
            'leadAssessor',
            'approvals.approver'
        ]);

        return view('admin.assessment-results.show', compact('assessmentResult'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssessmentResult $assessmentResult)
    {
        // Only allow editing if not yet approved
        if ($assessmentResult->approval_status === 'approved') {
            return redirect()->route('admin.assessment-results.show', $assessmentResult)
                ->with('error', 'Result yang sudah diapprove tidak dapat diubah');
        }

        $assessment = $assessmentResult->assessment()->with([
            'assessee',
            'scheme',
            'assessmentUnits.criteria'
        ])->first();

        $stats = $this->calculateAssessmentStats($assessment);

        return view('admin.assessment-results.edit', compact('assessmentResult', 'assessment', 'stats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssessmentResult $assessmentResult)
    {
        // Only allow editing if not yet approved
        if ($assessmentResult->approval_status === 'approved') {
            return redirect()->route('admin.assessment-results.show', $assessmentResult)
                ->with('error', 'Result yang sudah diapprove tidak dapat diubah');
        }

        $validated = $request->validate([
            'final_result' => 'required|in:competent,not_yet_competent,requires_reassessment',
            'executive_summary' => 'required|string',
            'key_strengths' => 'nullable|array',
            'development_areas' => 'nullable|array',
            'overall_performance_notes' => 'nullable|string',
            'recommendations' => 'nullable|array',
            'next_steps' => 'nullable|string',
            'reassessment_plan' => 'nullable|string',
            'certification_date' => 'nullable|date',
            'certification_expiry_date' => 'nullable|date',
            'certification_level' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $assessmentResult->update($validated);

        return redirect()->route('admin.assessment-results.show', $assessmentResult)
            ->with('success', 'Result assessment berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssessmentResult $assessmentResult)
    {
        // Only allow deletion if pending
        if ($assessmentResult->approval_status !== 'pending') {
            return redirect()->route('admin.assessment-results.index')
                ->with('error', 'Hanya result dengan status pending yang dapat dihapus');
        }

        $assessmentResult->delete();

        return redirect()->route('admin.assessment-results.index')
            ->with('success', 'Result assessment berhasil dihapus');
    }

    /**
     * Publish result
     */
    public function publish(AssessmentResult $assessmentResult)
    {
        if ($assessmentResult->approval_status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Result harus diapprove terlebih dahulu');
        }

        $assessmentResult->update([
            'is_published' => true,
            'published_at' => now(),
            'published_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Result berhasil dipublish');
    }

    /**
     * Issue certificate
     */
    public function issueCertificate(AssessmentResult $assessmentResult)
    {
        if ($assessmentResult->final_result !== 'competent') {
            return redirect()->back()
                ->with('error', 'Hanya result competent yang dapat diterbitkan sertifikat');
        }

        if ($assessmentResult->approval_status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Result harus diapprove terlebih dahulu');
        }

        $assessmentResult->update([
            'certificate_issued' => true,
            'certificate_issued_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Sertifikat berhasil diterbitkan');
    }

    /**
     * Calculate assessment statistics
     */
    private function calculateAssessmentStats(Assessment $assessment)
    {
        $units = $assessment->assessmentUnits()->with('criteria')->get();

        $totalCriteria = 0;
        $criteriaMet = 0;
        $criticalCriteriaTotal = 0;
        $criticalCriteriaMet = 0;
        $unitsCompetent = 0;
        $unitsNotYetCompetent = 0;
        $unitResults = [];

        foreach ($units as $unit) {
            $unitCriteriaTotal = $unit->criteria->count();
            $unitCriteriaMet = $unit->criteria->where('result', 'competent')->count();
            $unitCriticalTotal = $unit->criteria->where('is_critical', true)->count();
            $unitCriticalMet = $unit->criteria->where('is_critical', true)->where('result', 'competent')->count();

            $totalCriteria += $unitCriteriaTotal;
            $criteriaMet += $unitCriteriaMet;
            $criticalCriteriaTotal += $unitCriticalTotal;
            $criticalCriteriaMet += $unitCriticalMet;

            if ($unit->result === 'competent') {
                $unitsCompetent++;
            } elseif ($unit->result === 'not_yet_competent') {
                $unitsNotYetCompetent++;
            }

            $unitResults[] = [
                'unit_id' => $unit->id,
                'unit_code' => $unit->unit_code,
                'unit_title' => $unit->unit_title,
                'result' => $unit->result,
                'score' => $unit->score,
                'criteria_met' => $unitCriteriaMet,
                'total_criteria' => $unitCriteriaTotal,
            ];
        }

        $criteriaPercentage = $totalCriteria > 0 ? ($criteriaMet / $totalCriteria) * 100 : 0;
        $allCriticalCriteriaMet = $criticalCriteriaTotal > 0 && $criticalCriteriaMet === $criticalCriteriaTotal;

        return [
            'overall_score' => $criteriaPercentage,
            'units_assessed' => $units->count(),
            'units_competent' => $unitsCompetent,
            'units_not_yet_competent' => $unitsNotYetCompetent,
            'total_criteria' => $totalCriteria,
            'criteria_met' => $criteriaMet,
            'criteria_percentage' => $criteriaPercentage,
            'critical_criteria_total' => $criticalCriteriaTotal,
            'critical_criteria_met' => $criticalCriteriaMet,
            'all_critical_criteria_met' => $allCriticalCriteriaMet,
            'unit_results' => $unitResults,
        ];
    }
}
