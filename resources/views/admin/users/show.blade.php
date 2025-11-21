@extends('layouts.admin')

@section('title', 'User Details')

@php
    $active = 'users';
@endphp

@section('page_title', $user->name)
@section('page_description', 'User account details')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: User Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">User Information</h3>
                    <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit User</span>
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $user->name }}</h4>
                            @if($user->username)
                                <p class="text-sm text-gray-600">@{{ $user->username }}</p>
                            @endif
                            <div class="mt-2">
                                @if($user->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">mail</span>
                            <div>
                                <p class="text-xs text-gray-600">Email</p>
                                <p class="font-semibold text-gray-900">{{ $user->email }}</p>
                            </div>
                        </div>

                        @if($user->phone)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">phone</span>
                                <div>
                                    <p class="text-xs text-gray-600">Phone</p>
                                    <p class="font-semibold text-gray-900">{{ $user->phone }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigned Roles -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assigned Roles</h3>

                @if($user->roles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($user->roles as $role)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-indigo-600">shield</span>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $role->name }}</h4>
                                        @if($role->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $role->description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-2">
                                            {{ $role->permissions->count() }} permissions
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 text-5xl">shield</span>
                        <p class="text-gray-500 mt-2">No roles assigned</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Metadata -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Account Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Account Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">UUID</p>
                        <p class="font-mono text-xs text-gray-900 break-all">{{ $user->uuid }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Created</p>
                        <p class="font-semibold text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Last Updated</p>
                        <p class="font-semibold text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</p>
                    </div>
                    @if($user->last_login)
                        <div>
                            <p class="text-gray-600">Last Login</p>
                            <p class="font-semibold text-gray-900">{{ $user->last_login->diffForHumans() }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit User</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        <span>Back to List</span>
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition text-sm">
                                <span class="material-symbols-outlined text-sm">delete</span>
                                <span>Delete User</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
