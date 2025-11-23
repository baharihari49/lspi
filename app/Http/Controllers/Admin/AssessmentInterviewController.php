<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentInterview;
use App\Models\AssessmentUnit;
use App\Models\AssessmentCriteria;
use App\Models\User;
use Illuminate\Http\Request;

class AssessmentInterviewController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessmentInterview::with(['assessmentUnit.assessment.assessee', 'interviewer'])
            ->orderBy('conducted_at', 'desc');

        if ($request->filled('assessment_unit_id')) {
            $query->where('assessment_unit_id', $request->assessment_unit_id);
        }

        if ($request->filled('interviewer_id')) {
            $query->where('interviewer_id', $request->interviewer_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('interview_number', 'like', "%{$search}%")
                  ->orWhere('session_title', 'like', "%{$search}%");
            });
        }

        $interviews = $query->paginate(15);

        return view('admin.assessment-interviews.index', compact('interviews'));
    }

    public function create(Request $request)
    {
        $units = AssessmentUnit::with('assessment.assessee')->orderBy('created_at', 'desc')->limit(50)->get();
        $interviewers = User::withRole('assessor')->orderBy('name')->get();

        $selectedUnit = null;
        if ($request->filled('assessment_unit_id')) {
            $selectedUnit = AssessmentUnit::find($request->assessment_unit_id);
        }

        return view('admin.assessment-interviews.create', compact('units', 'interviewers', 'selectedUnit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_unit_id' => 'required|exists:assessment_units,id',
            'assessment_criteria_id' => 'nullable|exists:assessment_criteria,id',
            'interviewer_id' => 'required|exists:users,id',
            'topic' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'interview_date' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'interview_type' => 'nullable|in:structured,semi_structured,unstructured',
            'questions' => 'nullable|string',
            'responses' => 'nullable|string',
            'key_points_discussed' => 'nullable|string',
            'knowledge_demonstrated' => 'nullable|string',
            'understanding_level' => 'nullable|in:excellent,good,satisfactory,needs_improvement',
            'score' => 'nullable|numeric|min:0|max:100',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'interviewer_notes' => 'nullable|string',
            'requires_follow_up' => 'boolean',
            'follow_up_notes' => 'nullable|string',
        ]);

        // Generate interview number
        $year = date('Y');
        $lastInterview = AssessmentInterview::where('interview_number', 'like', "INT-{$year}-%")
            ->orderBy('interview_number', 'desc')
            ->first();

        if ($lastInterview) {
            $lastNumber = intval(substr($lastInterview->interview_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $validated['interview_number'] = "INT-{$year}-{$newNumber}";

        $interview = AssessmentInterview::create($validated);

        return redirect()->route('admin.assessment-interviews.show', $interview)
            ->with('success', 'Interview berhasil dibuat');
    }

    public function show(AssessmentInterview $assessmentInterview)
    {
        $assessmentInterview->load([
            'assessmentUnit.assessment.assessee',
            'assessmentCriteria',
            'interviewer',
        ]);

        return view('admin.assessment-interviews.show', compact('assessmentInterview'));
    }

    public function edit(AssessmentInterview $assessmentInterview)
    {
        $assessmentInterview->load(['assessmentUnit', 'assessmentCriteria']);
        $interviewers = User::withRole('assessor')->orderBy('name')->get();

        return view('admin.assessment-interviews.edit', compact('assessmentInterview', 'interviewers'));
    }

    public function update(Request $request, AssessmentInterview $assessmentInterview)
    {
        $validated = $request->validate([
            'interviewer_id' => 'required|exists:users,id',
            'session_title' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'conducted_at' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'interview_method' => 'required|in:face_to_face,video_conference,phone,written',
            'key_findings' => 'nullable|string',
            'competencies_demonstrated' => 'nullable|string',
            'gaps_identified' => 'nullable|string',
            'overall_assessment' => 'nullable|in:fully_satisfactory,satisfactory,needs_improvement,unsatisfactory',
            'score' => 'nullable|numeric|min:0|max:100',
            'interviewer_notes' => 'nullable|string',
            'behavioral_observations' => 'nullable|string',
            'technical_observations' => 'nullable|string',
            'requires_follow_up' => 'boolean',
            'follow_up_items' => 'nullable|string',
        ]);

        $assessmentInterview->update($validated);

        return redirect()->route('admin.assessment-interviews.show', $assessmentInterview)
            ->with('success', 'Interview berhasil diupdate');
    }

    public function destroy(AssessmentInterview $assessmentInterview)
    {
        $assessmentInterview->delete();

        return redirect()->route('admin.assessment-interviews.index')
            ->with('success', 'Interview berhasil dihapus');
    }
}
