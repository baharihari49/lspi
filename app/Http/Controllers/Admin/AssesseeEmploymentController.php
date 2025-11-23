<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessee;
use App\Models\AssesseeEmploymentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssesseeEmploymentController extends Controller
{
    public function index(Assessee $assessee)
    {
        $employmentInfo = $assessee->employmentInfo()
            ->orderBy('is_current', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.assessee-employment.index', compact('assessee', 'employmentInfo'));
    }

    public function create(Assessee $assessee)
    {
        return view('admin.assessee-employment.create', compact('assessee'));
    }

    public function store(Request $request, Assessee $assessee)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_industry' => 'nullable|string|max:255',
            'company_location' => 'nullable|string|max:255',
            'company_website' => 'nullable|url|max:255',
            'position_title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'employment_type' => 'required|in:full_time,part_time,contract,internship,freelance',
            'job_description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'responsibilities' => 'nullable|string',
            'achievements' => 'nullable|string',
            'skills_used' => 'nullable|string',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_title' => 'nullable|string|max:255',
            'supervisor_contact' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['assessee_id'] = $assessee->id;
        $validated['is_current'] = $request->has('is_current');

        // If this is current employment, unset others
        if ($validated['is_current']) {
            $assessee->employmentInfo()->update(['is_current' => false]);
        }

        AssesseeEmploymentInfo::create($validated);

        return redirect()
            ->route('admin.assessees.employment.index', $assessee)
            ->with('success', 'Employment information added successfully');
    }

    public function edit(Assessee $assessee, AssesseeEmploymentInfo $employment)
    {
        return view('admin.assessee-employment.edit', compact('assessee', 'employment'));
    }

    public function update(Request $request, Assessee $assessee, AssesseeEmploymentInfo $employment)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_industry' => 'nullable|string|max:255',
            'company_location' => 'nullable|string|max:255',
            'company_website' => 'nullable|url|max:255',
            'position_title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'employment_type' => 'required|in:full_time,part_time,contract,internship,freelance',
            'job_description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'responsibilities' => 'nullable|string',
            'achievements' => 'nullable|string',
            'skills_used' => 'nullable|string',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_title' => 'nullable|string|max:255',
            'supervisor_contact' => 'nullable|string|max:255',
            'verification_status' => 'required|in:pending,verified,rejected',
            'verification_notes' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_current'] = $request->has('is_current');

        // If this is current employment, unset others
        if ($validated['is_current']) {
            $assessee->employmentInfo()
                ->where('id', '!=', $employment->id)
                ->update(['is_current' => false]);
        }

        // Update verification timestamp if status changed to verified
        if ($validated['verification_status'] === 'verified' && $employment->verification_status !== 'verified') {
            $validated['verified_at'] = now();
            $validated['verified_by'] = Auth::id();
        }

        $employment->update($validated);

        return redirect()
            ->route('admin.assessees.employment.index', $assessee)
            ->with('success', 'Employment information updated successfully');
    }

    public function destroy(Assessee $assessee, AssesseeEmploymentInfo $employment)
    {
        $employment->delete();

        return redirect()
            ->route('admin.assessees.employment.index', $assessee)
            ->with('success', 'Employment information deleted successfully');
    }

    public function verify(Request $request, Assessee $assessee, AssesseeEmploymentInfo $employment)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $employment->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'] ?? null,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Employment verification updated successfully');
    }
}
