<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessor;
use App\Models\AssessorBankInfo;
use Illuminate\Http\Request;

class AssessorBankInfoController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessorBankInfo::with(['assessor', 'verifier', 'bankStatementFile', 'npwpFile']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bank_name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('account_holder_name', 'like', "%{$search}%")
                    ->orWhere('npwp_number', 'like', "%{$search}%")
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

        // Filter by primary
        if ($request->boolean('primary_only')) {
            $query->primary();
        }

        // Filter by active
        if ($request->boolean('active_only')) {
            $query->active();
        }

        $bankInfos = $query->latest()->paginate(15);
        $assessors = Assessor::where('is_active', true)->get();

        return view('admin.assessors.bank-info.index', compact('bankInfos', 'assessors'));
    }

    public function create()
    {
        $assessors = Assessor::where('is_active', true)->get();

        return view('admin.assessors.bank-info.create', compact('assessors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'bank_name' => 'required|string|max:255',
            'bank_code' => 'nullable|string|max:10',
            'branch_name' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:255',
            'npwp_number' => 'nullable|string|size:20',
            'tax_name' => 'nullable|string|max:255',
            'tax_address' => 'nullable|string',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['verification_status'] = 'pending';

        // If this is set as primary, unset other primary bank info for this assessor
        if ($validated['is_primary'] ?? false) {
            AssessorBankInfo::where('assessor_id', $validated['assessor_id'])
                ->update(['is_primary' => false]);
        }

        // Handle bank statement file upload
        if ($request->hasFile('bank_statement_file')) {
            $file = $request->file('bank_statement_file');
            $filename = time() . '_bank_statement_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/bank-statements', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['bank_statement_file_id'] = $fileRecord->id;
        }

        // Handle NPWP file upload
        if ($request->hasFile('npwp_file')) {
            $file = $request->file('npwp_file');
            $filename = time() . '_npwp_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/npwp', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['npwp_file_id'] = $fileRecord->id;
        }

        AssessorBankInfo::create($validated);

        return redirect()->route('admin.assessor-bank-info.index')
            ->with('success', 'Bank information created successfully.');
    }

    public function show(AssessorBankInfo $assessorBankInfo)
    {
        $assessorBankInfo->load(['assessor', 'verifier', 'bankStatementFile', 'npwpFile']);

        return view('admin.assessors.bank-info.show', compact('assessorBankInfo'));
    }

    public function edit(AssessorBankInfo $assessorBankInfo)
    {
        $assessors = Assessor::where('is_active', true)
            ->orWhere('id', $assessorBankInfo->assessor_id)
            ->get();

        return view('admin.assessors.bank-info.edit', compact('assessorBankInfo', 'assessors'));
    }

    public function update(Request $request, AssessorBankInfo $assessorBankInfo)
    {
        $validated = $request->validate([
            'assessor_id' => 'required|exists:assessors,id',
            'bank_name' => 'required|string|max:255',
            'bank_code' => 'nullable|string|max:10',
            'branch_name' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:255',
            'npwp_number' => 'nullable|string|size:20',
            'tax_name' => 'nullable|string|max:255',
            'tax_address' => 'nullable|string',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // If this is set as primary, unset other primary bank info for this assessor
        if ($validated['is_primary'] ?? false) {
            AssessorBankInfo::where('assessor_id', $validated['assessor_id'])
                ->where('id', '!=', $assessorBankInfo->id)
                ->update(['is_primary' => false]);
        }

        // Handle bank statement file upload
        if ($request->hasFile('bank_statement_file')) {
            $file = $request->file('bank_statement_file');
            $filename = time() . '_bank_statement_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/bank-statements', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['bank_statement_file_id'] = $fileRecord->id;
        }

        // Handle NPWP file upload
        if ($request->hasFile('npwp_file')) {
            $file = $request->file('npwp_file');
            $filename = time() . '_npwp_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents/npwp', $filename, 'public');

            $fileRecord = \App\Models\File::create([
                'storage_disk' => 'local',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'uploaded_by' => auth()->id(),
            ]);

            $validated['npwp_file_id'] = $fileRecord->id;
        }

        $assessorBankInfo->update($validated);

        return redirect()->route('admin.assessor-bank-info.index')
            ->with('success', 'Bank information updated successfully.');
    }

    public function destroy(AssessorBankInfo $assessorBankInfo)
    {
        $assessorBankInfo->delete();

        return redirect()->route('admin.assessor-bank-info.index')
            ->with('success', 'Bank information deleted successfully.');
    }

    // Verification action
    public function verify(Request $request, AssessorBankInfo $assessorBankInfo)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        $assessorBankInfo->update([
            'verification_status' => $validated['verification_status'],
            'verification_notes' => $validated['verification_notes'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Bank information verification status updated.');
    }
}
