<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationalPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationalStructureController extends Controller
{
    public function index()
    {
        $positions = OrganizationalPosition::with('parent')->orderBy('level')->orderBy('order')->get();
        return view('admin.organizational-structure.index', compact('positions'));
    }

    public function create()
    {
        $parents = OrganizationalPosition::rootLevel()->get();
        $allPositions = OrganizationalPosition::orderBy('level')->orderBy('order')->get();
        return view('admin.organizational-structure.create', compact('parents', 'allPositions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:organizational_positions,id',
            'order' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama harus diisi.',
            'position.required' => 'Jabatan harus diisi.',
            'level.required' => 'Level harus diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('organizational-photos', 'public');
        }

        OrganizationalPosition::create($validated);

        return redirect()->route('admin.organizational-structure.index')->with('success', 'Posisi berhasil ditambahkan!');
    }

    public function edit(OrganizationalPosition $organizationalStructure)
    {
        $allPositions = OrganizationalPosition::where('id', '!=', $organizationalStructure->id)->orderBy('level')->orderBy('order')->get();
        return view('admin.organizational-structure.edit', compact('organizationalStructure', 'allPositions'));
    }

    public function update(Request $request, OrganizationalPosition $organizationalStructure)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:organizational_positions,id',
            'order' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($organizationalStructure->photo) {
                Storage::disk('public')->delete($organizationalStructure->photo);
            }
            $validated['photo'] = $request->file('photo')->store('organizational-photos', 'public');
        }

        $organizationalStructure->update($validated);

        return redirect()->route('admin.organizational-structure.index')->with('success', 'Posisi berhasil diperbarui!');
    }

    public function destroy(OrganizationalPosition $organizationalStructure)
    {
        // Delete photo if exists
        if ($organizationalStructure->photo) {
            Storage::disk('public')->delete($organizationalStructure->photo);
        }

        $organizationalStructure->delete();
        return redirect()->route('admin.organizational-structure.index')->with('success', 'Posisi berhasil dihapus!');
    }
}
