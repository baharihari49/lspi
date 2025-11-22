@extends('layouts.admin')

@section('title', 'Edit Requirement')

@php
    $active = 'schemes';
@endphp

@section('page_title', 'Edit Requirement')

@section('content')
    <form action="{{ route('admin.schemes.versions.requirements.update', [$scheme, $version, $requirement]) }}" method="POST" class="w-full max-w-4xl">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="requirement_type" class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                            <select id="requirement_type" name="requirement_type" required class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="education" {{ old('requirement_type', $requirement->requirement_type) == 'education' ? 'selected' : '' }}>Education</option>
                                <option value="experience" {{ old('requirement_type', $requirement->requirement_type) == 'experience' ? 'selected' : '' }}>Experience</option>
                                <option value="certification" {{ old('requirement_type', $requirement->requirement_type) == 'certification' ? 'selected' : '' }}>Certification</option>
                                <option value="training" {{ old('requirement_type', $requirement->requirement_type) == 'training' ? 'selected' : '' }}>Training</option>
                                <option value="physical" {{ old('requirement_type', $requirement->requirement_type) == 'physical' ? 'selected' : '' }}>Physical</option>
                                <option value="other" {{ old('requirement_type', $requirement->requirement_type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $requirement->order) }}" min="0"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $requirement->title) }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $requirement->description) }}</textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', $requirement->is_mandatory) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm font-semibold text-gray-700">Mandatory Requirement</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">save</span>
                    <span>Update Requirement</span>
                </button>
                <a href="{{ route('admin.schemes.versions.show', [$scheme, $version]) }}" class="flex items-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">cancel</span>
                    <span>Cancel</span>
                </a>
            </div>
        </div>
    </form>
@endsection
