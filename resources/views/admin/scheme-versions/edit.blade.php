@extends('layouts.admin')

@section('title', 'Edit Version - ' . $scheme->name)

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Edit Version')
@section('page_description', $scheme->code . ' - ' . $scheme->name)

@section('content')
    <form action="{{ route('admin.schemes.versions.update', [$scheme, $version]) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-sm text-gray-600">
                    <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <a href="{{ route('admin.schemes.show', $scheme) }}" class="hover:text-blue-900">{{ $scheme->code }}</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <a href="{{ route('admin.schemes.versions.index', $scheme) }}" class="hover:text-blue-900">Versions</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-gray-900 font-semibold">Edit {{ $version->version }}</span>
                </nav>

                <!-- Version Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Version Information</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="version" class="block text-sm font-semibold text-gray-700 mb-2">Version Number *</label>
                            <input type="text" id="version" name="version" value="{{ old('version', $version->version) }}" required maxlength="50"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono @error('version') border-red-500 @enderror">
                            @error('version')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="changes_summary" class="block text-sm font-semibold text-gray-700 mb-2">Changes Summary</label>
                            <textarea id="changes_summary" name="changes_summary" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('changes_summary') border-red-500 @enderror">{{ old('changes_summary', $version->changes_summary) }}</textarea>
                            @error('changes_summary')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status & Dates -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status & Dates</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status') border-red-500 @enderror">
                                <option value="draft" {{ old('status', $version->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $version->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archived" {{ old('status', $version->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                <option value="deprecated" {{ old('status', $version->status) == 'deprecated' ? 'selected' : '' }}>Deprecated</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_current" value="1" {{ old('is_current', $version->is_current) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Set as Current Version</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="effective_date" class="block text-sm font-semibold text-gray-700 mb-2">Effective Date</label>
                                <input type="date" id="effective_date" name="effective_date" value="{{ old('effective_date', $version->effective_date?->format('Y-m-d')) }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('effective_date') border-red-500 @enderror">
                            </div>

                            <div>
                                <label for="expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $version->expiry_date?->format('Y-m-d')) }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('expiry_date') border-red-500 @enderror">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Update Version</span>
                            </button>
                            <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
