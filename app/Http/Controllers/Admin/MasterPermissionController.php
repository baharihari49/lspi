<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterPermissionController extends Controller
{
    public function index()
    {
        $permissions = MasterPermission::withCount('roles')->orderBy('name')->paginate(20);
        return view('admin.master-data.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.master-data.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:master_permissions,slug',
            'description' => 'nullable|string',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        MasterPermission::create($validated);

        return redirect()
            ->route('admin.master-permissions.index')
            ->with('success', 'Permission created successfully!');
    }

    public function edit(MasterPermission $masterPermission)
    {
        $rolesCount = $masterPermission->roles()->count();
        return view('admin.master-data.permissions.edit', compact('masterPermission', 'rolesCount'));
    }

    public function update(Request $request, MasterPermission $masterPermission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:master_permissions,slug,' . $masterPermission->id,
            'description' => 'nullable|string',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $masterPermission->update($validated);

        return redirect()
            ->route('admin.master-permissions.index')
            ->with('success', 'Permission updated successfully!');
    }

    public function destroy(MasterPermission $masterPermission)
    {
        // Check if permission is assigned to any roles
        if ($masterPermission->roles()->count() > 0) {
            return redirect()
                ->route('admin.master-permissions.index')
                ->with('error', 'Cannot delete permission that is assigned to roles!');
        }

        $masterPermission->delete();

        return redirect()
            ->route('admin.master-permissions.index')
            ->with('success', 'Permission deleted successfully!');
    }
}
