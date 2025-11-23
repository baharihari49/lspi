<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentDocument;
use App\Models\Assessment;
use App\Models\AssessmentUnit;
use App\Models\AssessmentCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssessmentDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AssessmentDocument::with([
            'assessment.assessee',
            'assessmentUnit',
            'uploader',
            'verifier'
        ])->orderBy('created_at', 'desc');

        // Filter by assessment
        if ($request->filled('assessment_id')) {
            $query->where('assessment_id', $request->assessment_id);
        }

        // Filter by verification status
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Filter by evidence type
        if ($request->filled('evidence_type')) {
            $query->where('evidence_type', $request->evidence_type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        $documents = $query->paginate(15);

        return view('admin.assessment-documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $assessments = Assessment::with('assessee')->orderBy('created_at', 'desc')->get();

        // Pre-select assessment if provided
        $selectedAssessment = null;
        if ($request->filled('assessment_id')) {
            $selectedAssessment = Assessment::find($request->assessment_id);
        }

        // Pre-select unit if provided
        $selectedUnit = null;
        if ($request->filled('assessment_unit_id')) {
            $selectedUnit = AssessmentUnit::find($request->assessment_unit_id);
        }

        return view('admin.assessment-documents.create', compact('assessments', 'selectedAssessment', 'selectedUnit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'assessment_unit_id' => 'nullable|exists:assessment_units,id',
            'assessment_criteria_id' => 'nullable|exists:assessment_criteria,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|in:evidence,certificate,report,photo,video,other',
            'evidence_type' => 'nullable|in:direct,indirect,supplementary,historical',
            'file' => 'required|file|max:10240', // 10MB max
            'relevance' => 'nullable|in:highly_relevant,relevant,partially_relevant,not_relevant',
            'assessor_comments' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            // Generate unique filename
            $filename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME))
                . '-' . time()
                . '.' . $extension;

            // Store file
            $path = $file->storeAs('assessment-documents', $filename, 'public');

            $validated['file_path'] = $path;
            $validated['file_name'] = $filename;
            $validated['file_type'] = $file->getClientMimeType();
            $validated['file_size'] = $file->getSize();
            $validated['original_filename'] = $originalFilename;
        }

        // Generate document number
        $year = date('Y');
        $lastDocument = AssessmentDocument::where('document_number', 'like', "DOC-{$year}-%")
            ->orderBy('document_number', 'desc')
            ->first();

        if ($lastDocument) {
            $lastNumber = intval(substr($lastDocument->document_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $validated['document_number'] = "DOC-{$year}-{$newNumber}";
        $validated['uploaded_by'] = auth()->id();
        $validated['verification_status'] = 'pending';

        $document = AssessmentDocument::create($validated);

        return redirect()->route('admin.assessment-documents.show', $document)
            ->with('success', 'Document berhasil diupload');
    }

    /**
     * Display the specified resource.
     */
    public function show(AssessmentDocument $assessmentDocument)
    {
        $assessmentDocument->load([
            'assessment.assessee',
            'assessmentUnit',
            'assessmentCriteria',
            'uploader',
            'verifier',
        ]);

        return view('admin.assessment-documents.show', compact('assessmentDocument'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssessmentDocument $assessmentDocument)
    {
        $assessmentDocument->load(['assessment', 'assessmentUnit', 'assessmentCriteria']);

        return view('admin.assessment-documents.edit', compact('assessmentDocument'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssessmentDocument $assessmentDocument)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|in:evidence,certificate,report,photo,video,other',
            'evidence_type' => 'nullable|in:direct,indirect,supplementary,historical',
            'relevance' => 'nullable|in:highly_relevant,relevant,partially_relevant,not_relevant',
            'assessor_comments' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Handle file replacement if new file uploaded
        if ($request->hasFile('file')) {
            // Delete old file
            if ($assessmentDocument->file_path && Storage::disk('public')->exists($assessmentDocument->file_path)) {
                Storage::disk('public')->delete($assessmentDocument->file_path);
            }

            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $filename = Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME))
                . '-' . time()
                . '.' . $extension;

            $path = $file->storeAs('assessment-documents', $filename, 'public');

            $validated['file_path'] = $path;
            $validated['file_name'] = $filename;
            $validated['file_type'] = $file->getClientMimeType();
            $validated['file_size'] = $file->getSize();
            $validated['original_filename'] = $originalFilename;
        }

        $assessmentDocument->update($validated);

        return redirect()->route('admin.assessment-documents.show', $assessmentDocument)
            ->with('success', 'Document berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssessmentDocument $assessmentDocument)
    {
        // Delete file from storage
        if ($assessmentDocument->file_path && Storage::disk('public')->exists($assessmentDocument->file_path)) {
            Storage::disk('public')->delete($assessmentDocument->file_path);
        }

        $assessmentDocument->delete();

        return redirect()->route('admin.assessment-documents.index')
            ->with('success', 'Document berhasil dihapus');
    }

    /**
     * Download document
     */
    public function download(AssessmentDocument $assessmentDocument)
    {
        if (!$assessmentDocument->file_path || !Storage::disk('public')->exists($assessmentDocument->file_path)) {
            return redirect()->back()
                ->with('error', 'File tidak ditemukan');
        }

        return Storage::disk('public')->download(
            $assessmentDocument->file_path,
            $assessmentDocument->original_filename
        );
    }

    /**
     * Verify document
     */
    public function verify(Request $request, AssessmentDocument $assessmentDocument)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
            'relevance' => 'required|in:highly_relevant,relevant,partially_relevant,not_relevant',
        ]);

        $validated['verified_by'] = auth()->id();
        $validated['verified_at'] = now();

        $assessmentDocument->update($validated);

        $status = $validated['verification_status'] === 'verified' ? 'verified' : 'rejected';

        return redirect()->back()
            ->with('success', "Document berhasil {$status}");
    }

    /**
     * Bulk verify documents
     */
    public function bulkVerify(Request $request)
    {
        $validated = $request->validate([
            'document_ids' => 'required|array|min:1',
            'document_ids.*' => 'exists:assessment_documents,id',
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $updated = 0;

        foreach ($validated['document_ids'] as $documentId) {
            $document = AssessmentDocument::find($documentId);

            if ($document && $document->verification_status === 'pending') {
                $document->update([
                    'verification_status' => $validated['verification_status'],
                    'verification_notes' => $validated['verification_notes'] ?? $document->verification_notes,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                $updated++;
            }
        }

        return redirect()->back()
            ->with('success', "{$updated} documents berhasil diverifikasi");
    }

    /**
     * Get units for assessment (AJAX)
     */
    public function getUnits(Request $request)
    {
        $assessmentId = $request->get('assessment_id');

        $units = AssessmentUnit::where('assessment_id', $assessmentId)
            ->orderBy('display_order')
            ->get(['id', 'unit_code', 'unit_title']);

        return response()->json($units);
    }

    /**
     * Get criteria for unit (AJAX)
     */
    public function getCriteria(Request $request)
    {
        $unitId = $request->get('assessment_unit_id');

        $criteria = AssessmentCriteria::where('assessment_unit_id', $unitId)
            ->orderBy('display_order')
            ->get(['id', 'element_code', 'element_title']);

        return response()->json($criteria);
    }
}
