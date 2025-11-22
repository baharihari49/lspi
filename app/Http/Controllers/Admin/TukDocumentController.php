<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TukDocument;
use App\Models\Tuk;
use App\Models\MasterDocumentType;
use App\Models\MasterStatus;
use Illuminate\Http\Request;

class TukDocumentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tukId = $request->input('tuk_id');

        $documents = TukDocument::with(['tuk', 'documentType', 'status', 'uploader', 'file'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($tukId, function ($query, $tukId) {
                $query->where('tuk_id', $tukId);
            })
            ->paginate(10)
            ->withQueryString();

        $tuks = Tuk::active()->get();

        return view('admin.tuk.documents.index', compact('documents', 'search', 'tukId', 'tuks'));
    }

    public function create()
    {
        $tuks = Tuk::active()->get();
        $documentTypes = MasterDocumentType::all();
        $statuses = MasterStatus::where('category', 'document')->get();
        return view('admin.tuk.documents.create', compact('tuks', 'documentTypes', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tuk_id' => 'required|exists:tuk,id',
            'document_type_id' => 'required|exists:master_document_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issued_date',
            'status_id' => 'nullable|exists:master_statuses,id',
        ]);

        // Handle file upload
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/tuk', $filename, 'public');

            // Create file record
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

        $validated['uploaded_by'] = auth()->id();

        TukDocument::create($validated);

        return redirect()->route('admin.tuk-documents.index')
            ->with('success', 'TUK document created successfully.');
    }

    public function edit(TukDocument $tukDocument)
    {
        $tuks = Tuk::active()->get();
        $documentTypes = MasterDocumentType::all();
        $statuses = MasterStatus::where('category', 'document')->get();
        return view('admin.tuk.documents.edit', compact('tukDocument', 'tuks', 'documentTypes', 'statuses'));
    }

    public function update(Request $request, TukDocument $tukDocument)
    {
        $validated = $request->validate([
            'tuk_id' => 'required|exists:tuk,id',
            'document_type_id' => 'required|exists:master_document_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_id' => 'required|exists:files,id',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issued_date',
            'status_id' => 'nullable|exists:master_statuses,id',
        ]);

        $tukDocument->update($validated);

        return redirect()->route('admin.tuk-documents.index')
            ->with('success', 'TUK document updated successfully.');
    }

    public function destroy(TukDocument $tukDocument)
    {
        $tukDocument->delete();

        return redirect()->route('admin.tuk-documents.index')
            ->with('success', 'TUK document deleted successfully.');
    }
}
