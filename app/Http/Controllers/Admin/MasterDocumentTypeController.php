<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterDocumentType;
use Illuminate\Http\Request;

class MasterDocumentTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $documentTypes = MasterDocumentType::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.master-data.document-types.index', compact('documentTypes', 'search'));
    }

    public function create()
    {
        return view('admin.master-data.document-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:master_document_types,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'retention_months' => 'nullable|integer|min:0',
        ]);

        MasterDocumentType::create($validated);

        return redirect()
            ->route('admin.master-document-types.index')
            ->with('success', 'Document type created successfully!');
    }

    public function edit(MasterDocumentType $masterDocumentType)
    {
        return view('admin.master-data.document-types.edit', compact('masterDocumentType'));
    }

    public function update(Request $request, MasterDocumentType $masterDocumentType)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:master_document_types,code,' . $masterDocumentType->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'retention_months' => 'nullable|integer|min:0',
        ]);

        $masterDocumentType->update($validated);

        return redirect()
            ->route('admin.master-document-types.index')
            ->with('success', 'Document type updated successfully!');
    }

    public function destroy(MasterDocumentType $masterDocumentType)
    {
        $masterDocumentType->delete();

        return redirect()
            ->route('admin.master-document-types.index')
            ->with('success', 'Document type deleted successfully!');
    }
}
