@extends('layouts.admin')

@section('title', 'Edit Evidence')

@php
    $active = 'apl02-evidence';
@endphp

@section('page_title', 'Edit Evidence')
@section('page_description', 'Update evidence information for APL-02 portfolio assessment')

@section('content')
    <form action="{{ route('admin.apl02.evidence.update', $evidence) }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Evidence Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Evidence Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Evidence Number (Read-only) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Evidence Number</label>
                            <input type="text" value="{{ $evidence->evidence_number }}" readonly
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-600">Auto-generated, cannot be changed</p>
                        </div>

                        <!-- Evidence Type -->
                        <div>
                            <label for="evidence_type" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Type *</label>
                            <select id="evidence_type" name="evidence_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('evidence_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="document" {{ old('evidence_type', $evidence->evidence_type) === 'document' ? 'selected' : '' }}>Document</option>
                                <option value="certificate" {{ old('evidence_type', $evidence->evidence_type) === 'certificate' ? 'selected' : '' }}>Certificate</option>
                                <option value="work_sample" {{ old('evidence_type', $evidence->evidence_type) === 'work_sample' ? 'selected' : '' }}>Work Sample</option>
                                <option value="project" {{ old('evidence_type', $evidence->evidence_type) === 'project' ? 'selected' : '' }}>Project</option>
                                <option value="photo" {{ old('evidence_type', $evidence->evidence_type) === 'photo' ? 'selected' : '' }}>Photo</option>
                                <option value="video" {{ old('evidence_type', $evidence->evidence_type) === 'video' ? 'selected' : '' }}>Video</option>
                                <option value="presentation" {{ old('evidence_type', $evidence->evidence_type) === 'presentation' ? 'selected' : '' }}>Presentation</option>
                                <option value="log_book" {{ old('evidence_type', $evidence->evidence_type) === 'log_book' ? 'selected' : '' }}>Log Book</option>
                                <option value="portfolio" {{ old('evidence_type', $evidence->evidence_type) === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                <option value="other" {{ old('evidence_type', $evidence->evidence_type) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('evidence_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $evidence->title) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror">{{ old('description', $evidence->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Current File Info -->
                @if($evidence->file_path)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Current File</h3>

                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="material-symbols-outlined text-blue-600">description</span>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">{{ $evidence->original_filename ?? $evidence->file_name }}</p>
                                <p class="text-xs text-gray-600 mt-0.5">Uploaded: {{ $evidence->created_at->format('d M Y H:i') }}</p>
                            </div>
                            @if($evidence->file_path)
                                <a href="{{ route('admin.apl02.evidence.download', $evidence) }}" class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition text-sm font-semibold">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- File Upload -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Update File (Optional)</h3>
                    <p class="text-sm text-gray-600 mb-4">Upload a new file to replace the current one, or leave empty to keep the existing file.</p>

                    <div class="space-y-4">
                        <!-- File Upload -->
                        <div>
                            <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">Upload New File</label>
                            <input type="file" id="file" name="file"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('file') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-600">Maximum file size: 10MB. Supported formats: PDF, DOC, DOCX, JPG, PNG, MP4</p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- External URL (Alternative) -->
                        <div>
                            <label for="external_url" class="block text-sm font-semibold text-gray-700 mb-2">External URL (Alternative)</label>
                            <input type="url" id="external_url" name="external_url" value="{{ old('external_url', $evidence->external_url) }}" placeholder="https://..."
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('external_url') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-600">Use this if evidence is hosted externally (e.g., Google Drive, YouTube)</p>
                            @error('external_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Certificate/Issuer Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate/Issuer Information (Optional)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Issued By -->
                        <div>
                            <label for="issued_by" class="block text-sm font-semibold text-gray-700 mb-2">Issued By</label>
                            <input type="text" id="issued_by" name="issued_by" value="{{ old('issued_by', $evidence->issued_by) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('issued_by') border-red-500 @enderror">
                            @error('issued_by')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Issuer Organization -->
                        <div>
                            <label for="issuer_organization" class="block text-sm font-semibold text-gray-700 mb-2">Issuer Organization</label>
                            <input type="text" id="issuer_organization" name="issuer_organization" value="{{ old('issuer_organization', $evidence->issuer_organization) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('issuer_organization') border-red-500 @enderror">
                            @error('issuer_organization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certificate Number -->
                        <div>
                            <label for="certificate_number" class="block text-sm font-semibold text-gray-700 mb-2">Certificate Number</label>
                            <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number', $evidence->certificate_number) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certificate_number') border-red-500 @enderror">
                            @error('certificate_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Evidence Date -->
                        <div>
                            <label for="evidence_date" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Date</label>
                            <input type="date" id="evidence_date" name="evidence_date" value="{{ old('evidence_date', $evidence->evidence_date?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('evidence_date') border-red-500 @enderror">
                            @error('evidence_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Validity Start Date -->
                        <div>
                            <label for="validity_start_date" class="block text-sm font-semibold text-gray-700 mb-2">Validity Start Date</label>
                            <input type="date" id="validity_start_date" name="validity_start_date" value="{{ old('validity_start_date', $evidence->validity_start_date?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('validity_start_date') border-red-500 @enderror">
                            @error('validity_start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Validity End Date -->
                        <div>
                            <label for="validity_end_date" class="block text-sm font-semibold text-gray-700 mb-2">Validity End Date</label>
                            <input type="date" id="validity_end_date" name="validity_end_date" value="{{ old('validity_end_date', $evidence->validity_end_date?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('validity_end_date') border-red-500 @enderror">
                            @error('validity_end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Notes</h3>

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror">{{ old('notes', $evidence->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Public Checkbox -->
                    <div class="mt-4 flex items-start gap-3">
                        <input type="checkbox" id="is_public" name="is_public" value="1"
                            {{ old('is_public', $evidence->is_public) ? 'checked' : '' }}
                            class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                        <div class="flex-1">
                            <label for="is_public" class="block text-sm font-semibold text-gray-700 cursor-pointer">Make this evidence public</label>
                            <p class="text-xs text-gray-600 mt-0.5">Public evidence can be viewed by others</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Update Evidence</span>
                        </button>

                        <a href="{{ route('admin.apl02.evidence.show', $evidence) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Note:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Evidence number cannot be changed</li>
                            <li>• Upload a new file to replace the current one</li>
                            <li>• Leave file field empty to keep existing file</li>
                            <li>• Certificate information is optional</li>
                        </ul>
                    </div>

                    <!-- Metadata -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Metadata</h4>
                        <div class="space-y-2 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>Created:</span>
                                <span>{{ $evidence->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Updated:</span>
                                <span>{{ $evidence->updated_at->format('d M Y') }}</span>
                            </div>
                            @if($evidence->submitted_at)
                                <div class="flex justify-between">
                                    <span>Submitted:</span>
                                    <span>{{ $evidence->submitted_at->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
