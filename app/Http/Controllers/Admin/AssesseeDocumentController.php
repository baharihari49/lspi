<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessee;
use App\Models\AssesseeDocument;
use App\Models\MasterDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssesseeDocumentController extends Controller
{
    public function index(Assessee $assessee)
    {
        $documents = $assessee->documents()
            ->with(['documentType', 'verifier'])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.assessee-documents.index', compact('assessee', 'documents'));
    }

    public function create(Assessee $assessee)
    {
        $documentTypes = MasterDocumentType::orderBy('name')->get();

        return view('admin.assessee-documents.create', compact('assessee', 'documentTypes'));
    }

    public function store(Request $request, Assessee $assessee)
    {
        $validated = $request->validate([
            'document_type_id' => 'nullable|exists:master_document_types,id',
            'document_name' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'nullable|string|max:255',
            'is_required' => 'boolean',
            'is_public' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assessee-documents', $fileName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientMimeType();
            $validated['file_size'] = $file->getSize();
        }

        $validated['assessee_id'] = $assessee->id;
        $validated['is_required'] = $request->has('is_required');
        $validated['is_public'] = $request->has('is_public');
        $validated['uploaded_by'] = Auth::id();

        AssesseeDocument::create($validated);

        return redirect()
            ->route('admin.assessees.documents.index', $assessee)
            ->with('success', 'Document uploaded successfully');
    }

    public function edit(Assessee $assessee, AssesseeDocument $document)
    {
        $documentTypes = MasterDocumentType::orderBy('name')->get();

        return view('admin.assessee-documents.edit', compact('assessee', 'document', 'documentTypes'));
    }

    public function update(Request $request, Assessee $assessee, AssesseeDocument $document)
    {
        $validated = $request->validate([
            'document_type_id' => 'nullable|exists:master_document_types,id',
            'document_name' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // 10MB max
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'nullable|string|max:255',
            'verification_status' => 'required|in:pending,verified,rejected',
            'verification_notes' => 'nullable|string',
            'is_required' => 'boolean',
            'is_public' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assessee-documents', $fileName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_type'] = $file->getClientMimeType();
            $validated['file_size'] = $file->getSize();
        }

        $validated['is_required'] = $request->has('is_required');
        $validated['is_public'] = $request->has('is_public');

        // Update verification timestamp if status changed to verified
        if ($validated['verification_status'] === 'verified' && $document->verification_status !== 'verified') {
            $validated['verified_at'] = now();
            $validated['verified_by'] = Auth::id();
        }

        $document->update($validated);

        return redirect()
            ->route('admin.assessees.documents.index', $assessee)
            ->with('success', 'Document updated successfully');
    }

    public function destroy(Assessee $assessee, AssesseeDocument $document)
    {
        // Delete file
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('admin.assessees.documents.index', $assessee)
            ->with('success', 'Document deleted successfully');
    }

    public function download(Assessee $assessee, AssesseeDocument $document)
    {
        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function verify(Request $request, Assessee $assessee, AssesseeDocument $document)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $document->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'] ?? null,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Document verification updated successfully');
    }
}
