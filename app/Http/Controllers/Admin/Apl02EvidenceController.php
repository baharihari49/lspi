<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl02Evidence;
use App\Models\Apl02Unit;
use App\Models\Apl02EvidenceMap;
use App\Models\SchemeElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Apl02EvidenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Apl02Evidence::with(['assessee', 'apl02Unit', 'verifiedBy']);

        // Filters
        if ($request->filled('evidence_type')) {
            $query->where('evidence_type', $request->evidence_type);
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('assessment_result')) {
            $query->where('assessment_result', $request->assessment_result);
        }

        if ($request->filled('apl02_unit_id')) {
            $query->where('apl02_unit_id', $request->apl02_unit_id);
        }

        if ($request->filled('assessee_id')) {
            $query->where('assessee_id', $request->assessee_id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('evidence_number', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $evidence = $query->paginate(20);

        $units = Apl02Unit::with('assessee')->orderBy('unit_code')->get();

        return view('admin.apl02.evidence.index', compact('evidence', 'units'));
    }

    public function create(Request $request)
    {
        $unitId = $request->get('unit_id');
        $selectedUnit = $unitId ? Apl02Unit::with(['assessee', 'schemeUnit'])->findOrFail($unitId) : null;
        $units = Apl02Unit::with('assessee')->orderBy('unit_code')->get();

        return view('admin.apl02.evidence.create', compact('selectedUnit', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessee_id' => 'required|exists:assessees,id',
            'apl02_unit_id' => 'required|exists:apl02_units,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'evidence_type' => 'required|in:document,certificate,work_sample,project,photo,video,presentation,log_book,portfolio,other',
            'file' => 'nullable|file|max:10240', // 10MB max
            'external_url' => 'nullable|url|max:500',
            'evidence_date' => 'nullable|date',
            'validity_start_date' => 'nullable|date',
            'validity_end_date' => 'nullable|date|after_or_equal:validity_start_date',
            'issued_by' => 'nullable|string|max:255',
            'issuer_organization' => 'nullable|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'is_public' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('evidence', 'public');

            $validated['file_path'] = $path;
            $validated['file_name'] = $file->hashName();
            $validated['file_type'] = $file->getMimeType();
            $validated['file_size'] = $file->getSize();
            $validated['original_filename'] = $file->getClientOriginalName();
        }

        $validated['is_public'] = $request->has('is_public');
        $validated['submitted_at'] = now();
        $validated['submitted_by'] = auth()->id();

        $evidence = Apl02Evidence::create($validated);

        // Update unit evidence count
        $evidence->apl02Unit->updateEvidenceCount();

        return redirect()
            ->route('admin.apl02.evidence.show', $evidence)
            ->with('success', 'Evidence uploaded successfully.');
    }

    public function show(Apl02Evidence $evidence)
    {
        $evidence->load([
            'assessee',
            'apl02Unit.schemeUnit.elements',
            'verifiedBy',
            'submittedBy',
            'evidenceMaps.schemeElement',
            'evidenceMaps.evaluatedBy'
        ]);

        // Increment view count
        $evidence->incrementViewCount();

        $availableElements = SchemeElement::where('scheme_unit_id', $evidence->apl02Unit->scheme_unit_id)
            ->orderBy('order')
            ->get();

        return view('admin.apl02.evidence.show', compact('evidence', 'availableElements'));
    }

    public function edit(Apl02Evidence $evidence)
    {
        $units = Apl02Unit::with('assessee')->orderBy('unit_code')->get();

        return view('admin.apl02.evidence.edit', compact('evidence', 'units'));
    }

    public function update(Request $request, Apl02Evidence $evidence)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'evidence_type' => 'required|in:document,certificate,work_sample,project,photo,video,presentation,log_book,portfolio,other',
            'file' => 'nullable|file|max:10240',
            'external_url' => 'nullable|url|max:500',
            'evidence_date' => 'nullable|date',
            'validity_start_date' => 'nullable|date',
            'validity_end_date' => 'nullable|date|after_or_equal:validity_start_date',
            'issued_by' => 'nullable|string|max:255',
            'issuer_organization' => 'nullable|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'is_public' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($evidence->file_path) {
                Storage::disk('public')->delete($evidence->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('evidence', 'public');

            $validated['file_path'] = $path;
            $validated['file_name'] = $file->hashName();
            $validated['file_type'] = $file->getMimeType();
            $validated['file_size'] = $file->getSize();
            $validated['original_filename'] = $file->getClientOriginalName();
        }

        $validated['is_public'] = $request->has('is_public');

        $evidence->update($validated);

        return redirect()
            ->route('admin.apl02.evidence.show', $evidence)
            ->with('success', 'Evidence updated successfully.');
    }

    public function destroy(Apl02Evidence $evidence)
    {
        $unitId = $evidence->apl02_unit_id;

        $evidence->delete();

        // Update unit evidence count
        $unit = Apl02Unit::find($unitId);
        if ($unit) {
            $unit->updateEvidenceCount();
        }

        return redirect()
            ->route('admin.apl02.evidence.index')
            ->with('success', 'Evidence deleted successfully.');
    }

    // Additional actions
    public function verify(Request $request, Apl02Evidence $evidence)
    {
        $request->validate([
            'verification_notes' => 'nullable|string',
        ]);

        if ($evidence->verify(auth()->id(), $request->verification_notes)) {
            return redirect()
                ->route('admin.apl02.evidence.show', $evidence)
                ->with('success', 'Evidence verified successfully.');
        }

        return back()->with('error', 'Unable to verify evidence.');
    }

    public function reject(Request $request, Apl02Evidence $evidence)
    {
        $request->validate([
            'verification_notes' => 'required|string',
        ]);

        if ($evidence->reject(auth()->id(), $request->verification_notes)) {
            return redirect()
                ->route('admin.apl02.evidence.show', $evidence)
                ->with('success', 'Evidence rejected.');
        }

        return back()->with('error', 'Unable to reject evidence.');
    }

    public function assess(Request $request, Apl02Evidence $evidence)
    {
        $request->validate([
            'assessment_result' => 'required|in:valid,invalid,insufficient',
            'relevance_score' => 'nullable|numeric|min:0|max:100',
            'assessor_notes' => 'nullable|string',
        ]);

        if ($evidence->assess(
            $request->assessment_result,
            $request->relevance_score,
            $request->assessor_notes
        )) {
            return redirect()
                ->route('admin.apl02.evidence.show', $evidence)
                ->with('success', 'Evidence assessed successfully.');
        }

        return back()->with('error', 'Unable to assess evidence.');
    }

    public function mapToElement(Request $request, Apl02Evidence $evidence)
    {
        $request->validate([
            'scheme_element_id' => 'required|exists:scheme_elements,id',
            'coverage_level' => 'required|in:full,partial,supplementary',
            'coverage_percentage' => 'nullable|numeric|min:0|max:100',
            'mapping_notes' => 'nullable|string',
        ]);

        // Check if mapping already exists (including soft deleted)
        $existing = Apl02EvidenceMap::withTrashed()
            ->where('apl02_evidence_id', $evidence->id)
            ->where('scheme_element_id', $request->scheme_element_id)
            ->first();

        if ($existing) {
            // If soft deleted, restore it and update
            if ($existing->trashed()) {
                $existing->restore();
                $existing->update([
                    'coverage_level' => $request->coverage_level,
                    'coverage_percentage' => $request->coverage_percentage,
                    'mapping_notes' => $request->mapping_notes,
                ]);
                $map = $existing;
            } else {
                // Already exists and not deleted
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This evidence is already mapped to this element.',
                    ], 422);
                }
                return back()->with('error', 'This evidence is already mapped to this element.');
            }
        } else {
            $map = Apl02EvidenceMap::create([
                'apl02_evidence_id' => $evidence->id,
                'scheme_element_id' => $request->scheme_element_id,
                'coverage_level' => $request->coverage_level,
                'coverage_percentage' => $request->coverage_percentage,
                'mapping_notes' => $request->mapping_notes,
            ]);
        }

        // Update completion percentage
        $evidence->apl02Unit->calculateCompletionPercentage();
        $evidence->apl02Unit->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Evidence mapped to element successfully.',
                'map' => $map->load('schemeElement'),
            ]);
        }

        return redirect()
            ->route('admin.apl02.evidence.show', $evidence)
            ->with('success', 'Evidence mapped to element successfully.');
    }

    public function unmapFromElement(Request $request, Apl02Evidence $evidence, Apl02EvidenceMap $map)
    {
        $map->delete();

        // Update completion percentage
        $evidence->apl02Unit->calculateCompletionPercentage();
        $evidence->apl02Unit->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Evidence unmapped from element.',
            ]);
        }

        return redirect()
            ->route('admin.apl02.evidence.show', $evidence)
            ->with('success', 'Evidence unmapped from element.');
    }

    public function download(Apl02Evidence $evidence)
    {
        if (!$evidence->file_path) {
            return back()->with('error', 'No file available for download.');
        }

        if (!Storage::disk('public')->exists($evidence->file_path)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download(
            $evidence->file_path,
            $evidence->original_filename ?? $evidence->file_name
        );
    }

    /**
     * Update verification status via AJAX
     */
    public function updateVerificationStatus(Request $request, Apl02Evidence $evidence)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected,requires_clarification,pending',
            'notes' => 'nullable|string',
        ]);

        $status = $request->status;
        $notes = $request->notes;

        $evidence->verification_status = $status;
        $evidence->verified_by = auth()->id();
        $evidence->verified_at = now();
        $evidence->verification_notes = $notes;
        $evidence->save();

        return response()->json([
            'success' => true,
            'message' => 'Evidence verification status updated successfully.',
            'status' => $status,
            'status_label' => ucfirst(str_replace('_', ' ', $status)),
            'verified_by' => auth()->user()->name,
        ]);
    }
}
