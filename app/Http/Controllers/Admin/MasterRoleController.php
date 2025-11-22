<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPermission;
use App\Models\MasterRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $roles = MasterRole::withCount(['permissions', 'users'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.master-data.roles.index', compact('roles', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = MasterPermission::orderBy('name')->get();
        return view('admin.master-data.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:master_roles,slug',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:master_permissions,id',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $role = MasterRole::create($validated);

        // Attach permissions
        if (!empty($validated['permissions'])) {
            $role->permissions()->attach($validated['permissions']);
        }

        return redirect()
            ->route('admin.master-roles.index')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterRole $masterRole)
    {
        $permissions = MasterPermission::orderBy('name')->get();
        $rolePermissions = $masterRole->permissions->pluck('id')->toArray();

        return view('admin.master-data.roles.edit', compact('masterRole', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterRole $masterRole)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:master_roles,slug,' . $masterRole->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:master_permissions,id',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $masterRole->update($validated);

        // Sync permissions
        $masterRole->permissions()->sync($validated['permissions'] ?? []);

        return redirect()
            ->route('admin.master-roles.index')
            ->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterRole $masterRole)
    {
        // Check if role has users
        if ($masterRole->users()->count() > 0) {
            return redirect()
                ->route('admin.master-roles.index')
                ->with('error', 'Cannot delete role that has users assigned!');
        }

        $masterRole->permissions()->detach();
        $masterRole->delete();

        return redirect()
            ->route('admin.master-roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
