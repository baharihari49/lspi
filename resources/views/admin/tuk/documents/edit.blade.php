@extends('layouts.admin')

@section('title', 'Edit TUK Document')

@php
    $active = 'tuk-documents';
@endphp

@section('page_title', 'Edit TUK Document')
@section('page_description', 'Update document information')

@section('content')
    <form action="{{ route('admin.tuk-documents.update', $tukDocument) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Document Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Document Information</h3>

                    <div class="space-y-4">
                        <!-- TUK Selection -->
                        <div>
                            <label for="tuk_id" class="block text-sm font-semibold text-gray-700 mb-2">TUK *</label>
                            <select id="tuk_id" name="tuk_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tuk_id') border-red-500 @enderror">
                                <option value="">Select TUK</option>
                                @foreach($tuks as $tuk)
                                    <option value="{{ $tuk->id }}" {{ (old('tuk_id', $tukDocument->tuk_id) == $tuk->id) ? 'selected' : '' }}>
                                        {{ $tuk->name }} ({{ $tuk->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tuk_id')
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
                                    <option value="{{ $type->id }}" {{ (old('document_type_id', $tukDocument->document_type_id) == $type->id) ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('document_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Document Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $tukDocument->title) }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror"
                                placeholder="e.g., TUK Safety Certificate 2025">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror"
                                placeholder="Brief description of the document">{{ old('description', $tukDocument->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current File Display -->
                        @if($tukDocument->file)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current File</label>
                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-xl">description</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $tukDocument->file->filename }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($tukDocument->file->size / 1024, 2) }} KB • Uploaded {{ $tukDocument->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <a href="{{ Storage::url($tukDocument->file->path) }}" target="_blank"
                                   class="flex-shrink-0 p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Download">
                                    <span class="material-symbols-outlined">download</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- File ID (Hidden) -->
                        <input type="hidden" name="file_id" value="{{ $tukDocument->file_id }}">
                    </div>
                </div>

                <!-- Dates & Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Dates & Status</h3>

                    <div class="space-y-4">
                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="issued_date" class="block text-sm font-semibold text-gray-700 mb-2">Issued Date</label>
                                <input type="date" id="issued_date" name="issued_date" value="{{ old('issued_date', $tukDocument->issued_date?->format('Y-m-d')) }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('issued_date') border-red-500 @enderror">
                                @error('issued_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $tukDocument->expiry_date?->format('Y-m-d')) }}"
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('expiry_date') border-red-500 @enderror">
                                @error('expiry_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be after issued date</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status_id" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select id="status_id" name="status_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status_id') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ (old('status_id', $tukDocument->status_id) == $status->id) ? 'selected' : '' }}>
                                        {{ $status->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
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
                                <p class="text-xs text-gray-500 mb-1">TUK</p>
                                <p class="font-semibold text-gray-900">{{ $tukDocument->tuk->name }}</p>
                                <p class="text-xs text-gray-500 font-mono mt-1">{{ $tukDocument->tuk->code }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Document Type</p>
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                    {{ $tukDocument->documentType->name }}
                                </span>
                            </div>
                            @if($tukDocument->status)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Status</p>
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    {{ $tukDocument->status->label }}
                                </span>
                            </div>
                            @endif
                            @if($tukDocument->uploader)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Uploaded By</p>
                                <p class="text-sm text-gray-900">{{ $tukDocument->uploader->name }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Last Updated</p>
                                <p class="text-sm text-gray-900">{{ $tukDocument->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Help Information -->
                    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-2">Update Guidelines</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Verify all information</li>
                                    <li>• Update dates if changed</li>
                                    <li>• Set appropriate status</li>
                                    <li>• File cannot be changed</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Update Document</span>
                            </button>
                            <a href="{{ route('admin.tuk-documents.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-semibold text-gray-900">{{ $tukDocument->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated:</span>
                                <span class="font-semibold text-gray-900">{{ $tukDocument->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
