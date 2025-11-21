<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->withCount('roles')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = MasterRole::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'is_active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:master_roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        $user = User::create($validated);

        // Assign roles
        if (!empty($validated['roles'])) {
            foreach ($validated['roles'] as $roleId) {
                $user->roles()->attach($roleId, [
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                ]);
            }
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'profile', 'contacts']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = MasterRole::orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'is_active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:master_roles,id',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['updated_by'] = auth()->id();

        // Update password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Sync roles - detach old and attach new with metadata
        $user->roles()->detach();
        if (!empty($validated['roles'])) {
            foreach ($validated['roles'] as $roleId) {
                $user->roles()->attach($roleId, [
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                ]);
            }
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        // Soft delete
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
