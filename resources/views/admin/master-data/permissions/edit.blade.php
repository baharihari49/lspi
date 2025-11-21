@extends('layouts.admin')

@section('title', 'Edit Permission')

@php
    $active = 'master-data';
@endphp

@section('page_title', 'Edit Permission')
@section('page_description', 'Update permission information')

@section('content')
    <form action="{{ route('admin.master-permissions.update', $masterPermission) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Permission Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Permission Information</h3>

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Permission Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $masterPermission->name) }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                            placeholder="e.g. View Users, Edit News, Delete Certificates">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug (auto-generated if empty)</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $masterPermission->slug) }}"
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('slug') border-red-500 @enderror"
                            placeholder="e.g. users.view, news.edit, certificates.delete">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Use dot notation for permission slugs (e.g. module.action)</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('description') border-red-500 @enderror">{{ old('description', $masterPermission->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Update Permission</span>
                        </button>
                        <a href="{{ route('admin.master-permissions.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>

                <!-- Permission Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Permission Info</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Assigned Roles:</span>
                            <span class="font-semibold text-gray-900">{{ $rolesCount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created:</span>
                            <span class="font-semibold text-gray-900">{{ $masterPermission->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Updated:</span>
                            <span class="font-semibold text-gray-900">{{ $masterPermission->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                @if($rolesCount > 0)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-yellow-600 text-lg">warning</span>
                            <p class="text-xs text-yellow-800">This permission is assigned to {{ $rolesCount }} role(s). Changes will affect all users with these roles.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection
