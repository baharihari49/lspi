@extends('layouts.admin')

@section('title', 'Add Evidence')

@php
    $active = 'apl02-evidence';
@endphp

@section('page_title', 'Add New Evidence')
@section('page_description', 'Upload evidence for APL-02 portfolio assessment')

@section('content')
    <form action="{{ route('admin.apl02.evidence.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Evidence Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Evidence Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Portfolio Unit -->
                        <div>
                            <label for="apl02_unit_id" class="block text-sm font-semibold text-gray-700 mb-2">Portfolio Unit *</label>
                            <select id="apl02_unit_id" name="apl02_unit_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('apl02_unit_id') border-red-500 @enderror">
                                <option value="">Select Portfolio Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('apl02_unit_id', $selectedUnit?->id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->unit_code }} - {{ $unit->assessee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('apl02_unit_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Evidence Type -->
                        <div>
                            <label for="evidence_type" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Type *</label>
                            <select id="evidence_type" name="evidence_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('evidence_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="document" {{ old('evidence_type') === 'document' ? 'selected' : '' }}>Document</option>
                                <option value="certificate" {{ old('evidence_type') === 'certificate' ? 'selected' : '' }}>Certificate</option>
                                <option value="work_sample" {{ old('evidence_type') === 'work_sample' ? 'selected' : '' }}>Work Sample</option>
                                <option value="project" {{ old('evidence_type') === 'project' ? 'selected' : '' }}>Project</option>
                                <option value="photo" {{ old('evidence_type') === 'photo' ? 'selected' : '' }}>Photo</option>
                                <option value="video" {{ old('evidence_type') === 'video' ? 'selected' : '' }}>Video</option>
                                <option value="presentation" {{ old('evidence_type') === 'presentation' ? 'selected' : '' }}>Presentation</option>
                                <option value="log_book" {{ old('evidence_type') === 'log_book' ? 'selected' : '' }}>Log Book</option>
                                <option value="portfolio" {{ old('evidence_type') === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                <option value="other" {{ old('evidence_type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('evidence_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">File Upload</h3>

                    <div class="space-y-4">
                        <!-- File Upload -->
                        <div>
                            <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">Upload File</label>
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
                            <input type="url" id="external_url" name="external_url" value="{{ old('external_url') }}" placeholder="https://..."
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
                            <input type="text" id="issued_by" name="issued_by" value="{{ old('issued_by') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('issued_by') border-red-500 @enderror">
                            @error('issued_by')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Issuer Organization -->
                        <div>
                            <label for="issuer_organization" class="block text-sm font-semibold text-gray-700 mb-2">Issuer Organization</label>
                            <input type="text" id="issuer_organization" name="issuer_organization" value="{{ old('issuer_organization') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('issuer_organization') border-red-500 @enderror">
                            @error('issuer_organization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certificate Number -->
                        <div>
                            <label for="certificate_number" class="block text-sm font-semibold text-gray-700 mb-2">Certificate Number</label>
                            <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certificate_number') border-red-500 @enderror">
                            @error('certificate_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Validity Start Date -->
                        <div>
                            <label for="validity_start_date" class="block text-sm font-semibold text-gray-700 mb-2">Validity Start Date</label>
                            <input type="date" id="validity_start_date" name="validity_start_date" value="{{ old('validity_start_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('validity_start_date') border-red-500 @enderror">
                            @error('validity_start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Validity End Date -->
                        <div>
                            <label for="validity_end_date" class="block text-sm font-semibold text-gray-700 mb-2">Validity End Date</label>
                            <input type="date" id="validity_end_date" name="validity_end_date" value="{{ old('validity_end_date') }}"
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
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                            <span>Add Evidence</span>
                        </button>

                        <a href="{{ route('admin.apl02.evidence.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Note:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Evidence number will be auto-generated</li>
                            <li>• File or external URL is optional but recommended</li>
                            <li>• Evidence will be created with "Pending" verification status</li>
                            <li>• You can map evidence to elements after creation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
