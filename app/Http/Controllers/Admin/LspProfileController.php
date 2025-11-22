<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LspProfile;
use App\Models\MasterStatus;
use Illuminate\Http\Request;

class LspProfileController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $profiles = LspProfile::with(['status', 'logo'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('legal_name', 'like', "%{$search}%")
                      ->orWhere('license_number', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.lsp.profiles.index', compact('profiles', 'search'));
    }

    public function create()
    {
        $statuses = MasterStatus::where('category', 'general')->get();
        return view('admin.lsp.profiles.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:lsp_profiles,code',
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'license_number' => 'required|string|max:255|unique:lsp_profiles,license_number',
            'license_issued_date' => 'nullable|date',
            'license_expiry_date' => 'nullable|date',
            'accreditation_number' => 'nullable|string|max:255',
            'accreditation_expiry_date' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'fax' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'director_name' => 'nullable|string|max:255',
            'director_position' => 'nullable|string|max:255',
            'director_phone' => 'nullable|string|max:255',
            'director_email' => 'nullable|email|max:255',
            'status_id' => 'nullable|exists:master_statuses,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        LspProfile::create($validated);

        return redirect()->route('admin.lsp-profiles.index')
            ->with('success', 'LSP Profile created successfully.');
    }

    public function show(LspProfile $lspProfile)
    {
        $lspProfile->load(['status', 'logo']);
        return view('admin.lsp.profiles.show', compact('lspProfile'));
    }

    public function edit(LspProfile $lspProfile)
    {
        $statuses = MasterStatus::where('category', 'general')->get();
        return view('admin.lsp.profiles.edit', compact('lspProfile', 'statuses'));
    }

    public function update(Request $request, LspProfile $lspProfile)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:lsp_profiles,code,' . $lspProfile->id,
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'license_number' => 'required|string|max:255|unique:lsp_profiles,license_number,' . $lspProfile->id,
            'license_issued_date' => 'nullable|date',
            'license_expiry_date' => 'nullable|date',
            'accreditation_number' => 'nullable|string|max:255',
            'accreditation_expiry_date' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'fax' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'director_name' => 'nullable|string|max:255',
            'director_position' => 'nullable|string|max:255',
            'director_phone' => 'nullable|string|max:255',
            'director_email' => 'nullable|email|max:255',
            'status_id' => 'nullable|exists:master_statuses,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $lspProfile->update($validated);

        return redirect()->route('admin.lsp-profiles.index')
            ->with('success', 'LSP Profile updated successfully.');
    }

    public function destroy(LspProfile $lspProfile)
    {
        $lspProfile->delete();

        return redirect()->route('admin.lsp-profiles.index')
            ->with('success', 'LSP Profile deleted successfully.');
    }
}
