@extends('layouts.admin')

@section('title', 'Add Assessor Document')

@php
    $active = 'assessor-documents';
@endphp

@section('page_title', 'Add Assessor Document')
@section('page_description', 'Upload and manage assessor certificates and documents')

@section('content')
    <form action="{{ route('admin.assessor-documents.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Document Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Document Information</h3>

                    <div class="space-y-4">
                        <!-- Assessor -->
                        <div>
                            <label for="assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessor *</label>
                            <select id="assessor_id" name="assessor_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_id') border-red-500 @enderror">
                                <option value="">Select Assessor</option>
                                @foreach($assessors as $assessor)
                                    <option value="{{ $assessor->id }}" {{ old('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                        {{ $assessor->full_name }} ({{ $assessor->registration_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assessor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Type -->
                        <div>
                            <label for="document_type_id" class="block text-sm font-semibold text-gray-700 mb-2">Document Type *</label>
                            <select id="document_type_id" name="document_type_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('document_type_id') border-red-500 @enderror">
                                <option value="">Select Document Type</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('document_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Document Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror"
                                placeholder="e.g., Sertifikat Asesor Kompetensi">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Additional notes or description about this document">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Number -->
                        <div>
                            <label for="document_number" class="block text-sm font-semibold text-gray-700 mb-2">Document Number</label>
                            <input type="text" id="document_number" name="document_number" value="{{ old('document_number') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('document_number') border-red-500 @enderror"
                                placeholder="e.g., CERT-2025-001">
                            @error('document_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Validity Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Validity Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Issued Date -->
                        <div>
                            <label for="issued_date" class="block text-sm font-semibold text-gray-700 mb-2">Issued Date</label>
                            <input type="date" id="issued_date" name="issued_date" value="{{ old('issued_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('issued_date') border-red-500 @enderror">
                            @error('issued_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label for="expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('expiry_date') border-red-500 @enderror">
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank if the document does not expire</p>
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label for="status_id" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="status_id" name="status_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status_id') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Document File</h3>

                    <div>
                        <label for="document_file" class="block text-sm font-semibold text-gray-700 mb-2">Upload Document</label>
                        <input type="file" id="document_file" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('document_file') border-red-500 @enderror">
                        @error('document_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <span class="material-symbols-outlined text-sm align-middle">info</span>
                            Supported formats: PDF, DOC, DOCX, JPG, PNG. Max size: 5MB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Info -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                <!-- Help Information -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">info</span>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-2">Document Guidelines</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Upload clear and legible documents</li>
                                <li>• Ensure document information is accurate</li>
                                <li>• Documents will be pending verification</li>
                                <li>• Expired documents will be flagged automatically</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Verification Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Verification Status</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="text-gray-700">New documents will be marked as <strong>Pending</strong></span>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">
                            Documents require admin verification before being approved for use.
                        </p>
                    </div>
                </div>

                   <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Create Document</span>
                        </button>
                        <a href="{{ route('admin.assessor-documents.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
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
