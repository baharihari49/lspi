<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessor;
use App\Models\AssessorDocument;
use App\Models\MasterDocumentType;
use App\Models\MasterStatus;
use Illuminate\Http\Request;

class AssessorDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessorDocument::with(['assessor', 'documentType', 'file', 'status', 'verifier']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
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

        // Filter by verification status
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Filter by expiring soon
        if ($request->filled('expiring_soon')) {
            $query->expiringSoon($request->expiring_soon);
        }

        $documents = $query->latest()->paginate(15);
        $assessors = Assessor::where('is_active', true)->get();
        $documentTypes = MasterDocumentType::all();

        return view('admin.assessors.documents.index', compact('documents', 'assessors', 'documentTypes'));
    }

    public function create()
    {
        $assessors = Assessor::where('is_active', true)->get();
        $documentTypes = MasterDocumentType::all();
        $statuses = MasterStatus::all();

        return view('admin.assessors.documents.create', compact('assessors', 'documentTypes', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'document_type_id' => 'required|exists:master_document_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_number' => 'nullable|string|max:100',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issued_date',
            'status_id' => 'required|exists:master_statuses,id',
        ]);

        $validated['verification_status'] = 'pending';
        $validated['uploaded_by'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/assessors', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['file_id'] = $fileRecord->id;
        }

        AssessorDocument::create($validated);

        return redirect()->route('admin.assessor-documents.index')
            ->with('success', 'Document created successfully.');
    }

    public function show(AssessorDocument $assessorDocument)
    {
        $assessorDocument->load(['assessor', 'documentType', 'file', 'status', 'uploader', 'verifier']);

        return view('admin.assessors.documents.show', compact('assessorDocument'));
    }

    public function edit(AssessorDocument $assessorDocument)
    {
        $assessors = Assessor::where('is_active', true)
            ->orWhere('id', $assessorDocument->assessor_id)
            ->get();
        $documentTypes = MasterDocumentType::all();
        $statuses = MasterStatus::all();

        return view('admin.assessors.documents.edit', compact('assessorDocument', 'assessors', 'documentTypes', 'statuses'));
    }

    public function update(Request $request, AssessorDocument $assessorDocument)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'document_type_id' => 'required|exists:master_document_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_number' => 'nullable|string|max:100',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issued_date',
            'status_id' => 'required|exists:master_statuses,id',
        ]);

        // Handle file upload
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/assessors', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['file_id'] = $fileRecord->id;
        }

        $assessorDocument->update($validated);

        return redirect()->route('admin.assessor-documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(AssessorDocument $assessorDocument)
    {
        $assessorDocument->delete();

        return redirect()->route('admin.assessor-documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    // Verification action
    public function verify(Request $request, AssessorDocument $assessorDocument)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:approved,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $assessorDocument->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Document verification status updated.');
    }
}
