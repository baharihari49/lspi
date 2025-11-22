@extends('layouts.admin')

@section('title', 'Add Requirement')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Add Requirement')
@section('page_description', $scheme->code . ' - Version ' . $version->version)

@section('content')
    <form action="{{ route('admin.schemes.versions.requirements.store', [$scheme, $version]) }}" method="POST" class="w-full max-w-4xl">
        @csrf

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('admin.schemes.index') }}" class="hover:text-blue-900">Schemes</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('admin.schemes.show', $scheme) }}" class="hover:text-blue-900">{{ $scheme->code }}</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="hover:text-blue-900">v{{ $version->version }}</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-gray-900 font-semibold">Add Requirement</span>
            </nav>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Requirement Information</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="requirement_type" class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                            <select id="requirement_type" name="requirement_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('requirement_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="education" {{ old('requirement_type') == 'education' ? 'selected' : '' }}>Education</option>
                                <option value="experience" {{ old('requirement_type') == 'experience' ? 'selected' : '' }}>Experience</option>
                                <option value="certification" {{ old('requirement_type') == 'certification' ? 'selected' : '' }}>Certification</option>
                                <option value="training" {{ old('requirement_type') == 'training' ? 'selected' : '' }}>Training</option>
                                <option value="physical" {{ old('requirement_type') == 'physical' ? 'selected' : '' }}>Physical</option>
                                <option value="other" {{ old('requirement_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('requirement_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('order') border-red-500 @enderror">
                        </div>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('title') border-red-500 @enderror"
                            placeholder="e.g., Pendidikan Minimal S1">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none @error('description') border-red-500 @enderror"
                            placeholder="Detailed requirement description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', true) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm font-semibold text-gray-700">Mandatory Requirement</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">save</span>
                    <span>Create Requirement</span>
                </button>
                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Cancel</span>
                </a>
            </div>
        </div>
    </form>
@endsection
