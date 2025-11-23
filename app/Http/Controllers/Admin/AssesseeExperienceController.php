<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessee;
use App\Models\AssesseeExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssesseeExperienceController extends Controller
{
    public function index(Assessee $assessee, Request $request)
    {
        $query = $assessee->experiences();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('experience_type', $request->type);
        }

        $experiences = $query->orderBy('is_ongoing', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.assessee-experience.index', compact('assessee', 'experiences'));
    }

    public function create(Assessee $assessee)
    {
        return view('admin.assessee-experience.create', compact('assessee'));
    }

    public function store(Request $request, Assessee $assessee)
    {
        $validated = $request->validate([
            'experience_type' => 'required|in:professional,project,volunteer,certification,training,publication,award,other',
            'title' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_ongoing' => 'boolean',
            'responsibilities' => 'nullable|string',
            'outcomes' => 'nullable|string',
            'skills_gained' => 'nullable|string',
            // For certifications
            'certificate_number' => 'nullable|string|max:255',
            'issuing_organization' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            // For publications
            'publication_title' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'publication_url' => 'nullable|url|max:255',
            'doi' => 'nullable|string|max:255',
            // For awards
            'award_name' => 'nullable|string|max:255',
            'award_issuer' => 'nullable|string|max:255',
            'award_date' => 'nullable|date',
            // Documentation
            'evidence_file' => 'nullable|file|max:10240',
            'reference_url' => 'nullable|url|max:255',
            'relevance_to_certification' => 'nullable|string',
            'relevance_score' => 'nullable|integer|min:1|max:10',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle evidence file upload
        if ($request->hasFile('evidence_file')) {
            $file = $request->file('evidence_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assessee-experience', $fileName, 'public');
            $validated['evidence_file'] = $filePath;
        }

        $validated['assessee_id'] = $assessee->id;
        $validated['is_ongoing'] = $request->has('is_ongoing');

        AssesseeExperience::create($validated);

        return redirect()
            ->route('admin.assessees.experience.index', $assessee)
            ->with('success', 'Experience added successfully');
    }

    public function edit(Assessee $assessee, AssesseeExperience $experience)
    {
        return view('admin.assessee-experience.edit', compact('assessee', 'experience'));
    }

    public function update(Request $request, Assessee $assessee, AssesseeExperience $experience)
    {
        $validated = $request->validate([
            'experience_type' => 'required|in:professional,project,volunteer,certification,training,publication,award,other',
            'title' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_ongoing' => 'boolean',
            'responsibilities' => 'nullable|string',
            'outcomes' => 'nullable|string',
            'skills_gained' => 'nullable|string',
            // For certifications
            'certificate_number' => 'nullable|string|max:255',
            'issuing_organization' => 'nullable|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            // For publications
            'publication_title' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'publication_url' => 'nullable|url|max:255',
            'doi' => 'nullable|string|max:255',
            // For awards
            'award_name' => 'nullable|string|max:255',
            'award_issuer' => 'nullable|string|max:255',
            'award_date' => 'nullable|date',
            // Documentation
            'evidence_file' => 'nullable|file|max:10240',
            'reference_url' => 'nullable|url|max:255',
            'relevance_to_certification' => 'nullable|string',
            'relevance_score' => 'nullable|integer|min:1|max:10',
            'verification_status' => 'required|in:pending,verified,rejected',
            'verification_notes' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle evidence file upload
        if ($request->hasFile('evidence_file')) {
            // Delete old file
            if ($experience->evidence_file) {
                Storage::disk('public')->delete($experience->evidence_file);
            }

            $file = $request->file('evidence_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assessee-experience', $fileName, 'public');
            $validated['evidence_file'] = $filePath;
        }

        $validated['is_ongoing'] = $request->has('is_ongoing');

        // Update verification timestamp if status changed to verified
        if ($validated['verification_status'] === 'verified' && $experience->verification_status !== 'verified') {
            $validated['verified_at'] = now();
            $validated['verified_by'] = Auth::id();
        }

        $experience->update($validated);

        return redirect()
            ->route('admin.assessees.experience.index', $assessee)
            ->with('success', 'Experience updated successfully');
    }

    public function destroy(Assessee $assessee, AssesseeExperience $experience)
    {
        // Delete evidence file if exists
        if ($experience->evidence_file) {
            Storage::disk('public')->delete($experience->evidence_file);
        }

        $experience->delete();

        return redirect()
            ->route('admin.assessees.experience.index', $assessee)
            ->with('success', 'Experience deleted successfully');
    }

    public function verify(Request $request, Assessee $assessee, AssesseeExperience $experience)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $experience->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'] ?? null,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Experience verification updated successfully');
    }
}
