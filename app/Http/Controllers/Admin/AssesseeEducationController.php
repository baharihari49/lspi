<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessee;
use App\Models\AssesseeEducationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssesseeEducationController extends Controller
{
    public function index(Assessee $assessee)
    {
        $educationHistory = $assessee->educationHistory()
            ->orderBy('is_current', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.assessee-education.index', compact('assessee', 'educationHistory'));
    }

    public function create(Assessee $assessee)
    {
        return view('admin.assessee-education.create', compact('assessee'));
    }

    public function store(Request $request, Assessee $assessee)
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'institution_location' => 'nullable|string|max:255',
            'institution_type' => 'nullable|string|max:255',
            'education_level' => 'required|in:sd,smp,sma,diploma,s1,s2,s3,other',
            'degree_name' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'minor' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'gpa' => 'nullable|string|max:255',
            'gpa_scale' => 'nullable|string|max:255',
            'honors' => 'nullable|string|max:255',
            'achievements' => 'nullable|string',
            'certificate_number' => 'nullable|string|max:255',
            'graduation_date' => 'nullable|date',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['assessee_id'] = $assessee->id;
        $validated['is_current'] = $request->has('is_current');

        AssesseeEducationHistory::create($validated);

        return redirect()
            ->route('admin.assessees.education.index', $assessee)
            ->with('success', 'Education history added successfully');
    }

    public function edit(Assessee $assessee, AssesseeEducationHistory $education)
    {
        return view('admin.assessee-education.edit', compact('assessee', 'education'));
    }

    public function update(Request $request, Assessee $assessee, AssesseeEducationHistory $education)
    {
        $validated = $request->validate([
            'institution_name' => 'required|string|max:255',
            'institution_location' => 'nullable|string|max:255',
            'institution_type' => 'nullable|string|max:255',
            'education_level' => 'required|in:sd,smp,sma,diploma,s1,s2,s3,other',
            'degree_name' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'minor' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'gpa' => 'nullable|string|max:255',
            'gpa_scale' => 'nullable|string|max:255',
            'honors' => 'nullable|string|max:255',
            'achievements' => 'nullable|string',
            'certificate_number' => 'nullable|string|max:255',
            'graduation_date' => 'nullable|date',
            'verification_status' => 'required|in:pending,verified,rejected',
            'verification_notes' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_current'] = $request->has('is_current');

        // Update verification timestamp if status changed to verified
        if ($validated['verification_status'] === 'verified' && $education->verification_status !== 'verified') {
            $validated['verified_at'] = now();
            $validated['verified_by'] = Auth::id();
        }

        $education->update($validated);

        return redirect()
            ->route('admin.assessees.education.index', $assessee)
            ->with('success', 'Education history updated successfully');
    }

    public function destroy(Assessee $assessee, AssesseeEducationHistory $education)
    {
        $education->delete();

        return redirect()
            ->route('admin.assessees.education.index', $assessee)
            ->with('success', 'Education history deleted successfully');
    }

    public function verify(Request $request, Assessee $assessee, AssesseeEducationHistory $education)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $education->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'] ?? null,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Education verification updated successfully');
    }
}
