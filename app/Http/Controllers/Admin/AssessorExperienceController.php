<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessor;
use App\Models\AssessorExperience;
use Illuminate\Http\Request;

class AssessorExperienceController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessorExperience::with(['assessor', 'documentFile']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('organization_name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('assessor', function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%")
                            ->orWhere('registration_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by assessor
        if ($request->filled('assessor_id')) {
            $query->where('assessor_id', $request->assessor_id);
        }

        // Filter by experience type
        if ($request->filled('experience_type')) {
            $query->byType($request->experience_type);
        }

        // Filter by current
        if ($request->boolean('current_only')) {
            $query->current();
        }

        $experiences = $query->latest()->paginate(15);
        $assessors = Assessor::where('is_active', true)->get();

        return view('admin.assessors.experiences.index', compact('experiences', 'assessors'));
    }

    public function create()
    {
        $assessors = Assessor::where('is_active', true)->get();

        return view('admin.assessors.experiences.create', compact('assessors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'organization_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'experience_type' => 'required|in:assessment,training,industry,other',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'location' => 'nullable|string|max:255',
            'reference_name' => 'nullable|string|max:255',
            'reference_contact' => 'nullable|string|max:100',
        ]);

        // If is_current is true, set end_date to null
        if ($validated['is_current'] ?? false) {
            $validated['end_date'] = null;
        }

        // Handle document file upload
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/assessor-experience', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['document_file_id'] = $fileRecord->id;
        }

        AssessorExperience::create($validated);

        return redirect()->route('admin.assessor-experiences.index')
            ->with('success', 'Experience created successfully.');
    }

    public function show(AssessorExperience $assessorExperience)
    {
        $assessorExperience->load(['assessor', 'documentFile']);

        return view('admin.assessors.experiences.show', compact('assessorExperience'));
    }

    public function edit(AssessorExperience $assessorExperience)
    {
        $assessors = Assessor::where('is_active', true)
            ->orWhere('id', $assessorExperience->assessor_id)
            ->get();

        return view('admin.assessors.experiences.edit', compact('assessorExperience', 'assessors'));
    }

    public function update(Request $request, AssessorExperience $assessorExperience)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'organization_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'experience_type' => 'required|in:assessment,training,industry,other',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'location' => 'nullable|string|max:255',
            'reference_name' => 'nullable|string|max:255',
            'reference_contact' => 'nullable|string|max:100',
        ]);

        // If is_current is true, set end_date to null
        if ($validated['is_current'] ?? false) {
            $validated['end_date'] = null;
        }

        // Handle document file upload
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/assessor-experience', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['document_file_id'] = $fileRecord->id;
        }

        $assessorExperience->update($validated);

        return redirect()->route('admin.assessor-experiences.index')
            ->with('success', 'Experience updated successfully.');
    }

    public function destroy(AssessorExperience $assessorExperience)
    {
        $assessorExperience->delete();

        return redirect()->route('admin.assessor-experiences.index')
            ->with('success', 'Experience deleted successfully.');
    }
}
