@extends('layouts.admin')

@section('title', 'Edit Experience')

@php
    $active = 'assessor-experiences';
@endphp

@section('page_title', 'Edit Experience')
@section('page_description', 'Update assessor work experience information')

@section('content')
    <form action="{{ route('admin.assessor-experiences.update', $assessorExperience) }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Assessor Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Information</h3>

                    <div>
                        <label for="assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessor *</label>
                        <select id="assessor_id" name="assessor_id" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_id') border-red-500 @enderror">
                            <option value="">Select Assessor</option>
                            @foreach($assessors as $assessor)
                                <option value="{{ $assessor->id }}" {{ old('assessor_id', $assessorExperience->assessor_id) == $assessor->id ? 'selected' : '' }}>
                                    {{ $assessor->full_name }} ({{ $assessor->registration_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('assessor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Experience Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Experience Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Organization Name -->
                        <div class="md:col-span-2">
                            <label for="organization_name" class="block text-sm font-semibold text-gray-700 mb-2">Organization Name *</label>
                            <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name', $assessorExperience->organization_name) }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('organization_name') border-red-500 @enderror"
                                placeholder="e.g., PT. Teknologi Pendidikan Indonesia">
                            @error('organization_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">Position *</label>
                            <input type="text" id="position" name="position" value="{{ old('position', $assessorExperience->position) }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('position') border-red-500 @enderror"
                                placeholder="e.g., Senior Assessor">
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Experience Type -->
                        <div>
                            <label for="experience_type" class="block text-sm font-semibold text-gray-700 mb-2">Experience Type *</label>
                            <select id="experience_type" name="experience_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('experience_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="assessment" {{ old('experience_type', $assessorExperience->experience_type) === 'assessment' ? 'selected' : '' }}>Assessment</option>
                                <option value="training" {{ old('experience_type', $assessorExperience->experience_type) === 'training' ? 'selected' : '' }}>Training</option>
                                <option value="industry" {{ old('experience_type', $assessorExperience->experience_type) === 'industry' ? 'selected' : '' }}>Industry</option>
                                <option value="other" {{ old('experience_type', $assessorExperience->experience_type) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('experience_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="md:col-span-2">
                            <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $assessorExperience->location) }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('location') border-red-500 @enderror"
                                placeholder="e.g., Jakarta, Indonesia">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Period -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Period</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $assessorExperience->start_date?->format('Y-m-d')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $assessorExperience->end_date?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('end_date') border-red-500 @enderror"
                                {{ old('is_current', $assessorExperience->is_current) ? 'disabled' : '' }}>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank if this is a current position</p>
                        </div>

                        <!-- Is Current -->
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_current" value="1" {{ old('is_current', $assessorExperience->is_current) ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500"
                                    onchange="document.getElementById('end_date').disabled = this.checked; if(this.checked) document.getElementById('end_date').value = '';">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Current Position</p>
                                    <p class="text-xs text-gray-600">Check if currently working at this organization</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Description</h3>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Job Description & Responsibilities</label>
                        <textarea id="description" name="description" rows="5"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                            placeholder="Describe the main responsibilities, achievements, and key activities in this role...">{{ old('description', $assessorExperience->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reference Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Reference Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Reference Name -->
                        <div>
                            <label for="reference_name" class="block text-sm font-semibold text-gray-700 mb-2">Reference Name</label>
                            <input type="text" id="reference_name" name="reference_name" value="{{ old('reference_name', $assessorExperience->reference_name) }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('reference_name') border-red-500 @enderror"
                                placeholder="e.g., Dr. Ahmad Wijaya">
                            @error('reference_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reference Contact -->
                        <div>
                            <label for="reference_contact" class="block text-sm font-semibold text-gray-700 mb-2">Reference Contact</label>
                            <input type="text" id="reference_contact" name="reference_contact" value="{{ old('reference_contact', $assessorExperience->reference_contact) }}" maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('reference_contact') border-red-500 @enderror"
                                placeholder="e.g., ahmad@example.com or +62 812-3456-7890">
                            @error('reference_contact')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Supporting Document</h3>

                    @if($assessorExperience->document_file_path)
                        <div class="mb-4 flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl">description</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ basename($assessorExperience->document_file_path) }}</p>
                                <p class="text-xs text-gray-500 mt-1">Current document</p>
                            </div>
                            <a href="{{ asset('storage/' . $assessorExperience->document_file_path) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                                <span class="material-symbols-outlined text-sm">download</span>
                                <span>Download</span>
                            </a>
                        </div>
                    @endif

                    <div>
                        <label for="document_file" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $assessorExperience->document_file_path ? 'Replace Document File' : 'Document File' }}
                        </label>
                        <input type="file" id="document_file" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('document_file') border-red-500 @enderror">
                        @error('document_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Supported formats: PDF, DOC, DOCX, JPG, PNG. Max size: 5MB.
                            @if($assessorExperience->document_file_path)
                                Leave empty to keep the current document.
                            @else
                                Upload work certificate, reference letter, or other supporting documents.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Info -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                    <!-- Current Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Current Status</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Position Status</p>
                                @if($assessorExperience->is_current)
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        Current
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                        Past
                                    </span>
                                @endif
                            </div>

                            @if($assessorExperience->duration)
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Current Duration</p>
                                    <p class="font-semibold text-gray-900">
                                        @if($assessorExperience->duration >= 12)
                                            {{ floor($assessorExperience->duration / 12) }} year{{ floor($assessorExperience->duration / 12) > 1 ? 's' : '' }}
                                            @if($assessorExperience->duration % 12 > 0)
                                                {{ $assessorExperience->duration % 12 }} month{{ $assessorExperience->duration % 12 > 1 ? 's' : '' }}
                                            @endif
                                        @else
                                            {{ $assessorExperience->duration }} month{{ $assessorExperience->duration > 1 ? 's' : '' }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-semibold text-gray-900">{{ $assessorExperience->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated:</span>
                                <span class="font-semibold text-gray-900">{{ $assessorExperience->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Update Experience</span>
                            </button>
                            <a href="{{ route('admin.assessor-experiences.show', $assessorExperience) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
