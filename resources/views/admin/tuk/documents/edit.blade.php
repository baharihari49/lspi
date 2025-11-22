@extends('layouts.admin')

@section('title', 'Edit TUK Document')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit TUK Document</h1>
        <p class="text-gray-600 mt-2">Update document information</p>
    </div>

    <form action="{{ route('admin.tuk-documents.update', $tukDocument) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Document Information</h2>

                    <div class="space-y-4">
                        <!-- TUK Selection -->
                        <div>
                            <label for="tuk_id" class="block text-sm font-medium text-gray-700 mb-2">TUK <span class="text-red-500">*</span></label>
                            <select id="tuk_id" name="tuk_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tuk_id') border-red-500 @enderror">
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
                            <label for="document_type_id" class="block text-sm font-medium text-gray-700 mb-2">Document Type <span class="text-red-500">*</span></label>
                            <select id="document_type_id" name="document_type_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('document_type_id') border-red-500 @enderror">
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
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Document Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $tukDocument->title) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $tukDocument->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload (Placeholder) -->
                        <div>
                            <label for="file_id" class="block text-sm font-medium text-gray-700 mb-2">File ID <span class="text-red-500">*</span></label>
                            <input type="number" id="file_id" name="file_id" value="{{ old('file_id', $tukDocument->file_id) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file_id') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Placeholder: Use existing file ID (file upload feature to be implemented)</p>
                            @error('file_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dates & Status Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Dates & Status</h2>

                    <div class="space-y-4">
                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="issued_date" class="block text-sm font-medium text-gray-700 mb-2">Issued Date</label>
                                <input type="date" id="issued_date" name="issued_date" value="{{ old('issued_date', $tukDocument->issued_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('issued_date') border-red-500 @enderror">
                                @error('issued_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $tukDocument->expiry_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expiry_date') border-red-500 @enderror">
                                @error('expiry_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be after issued date</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status_id" name="status_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_id') border-red-500 @enderror">
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

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>

                    <div class="space-y-3">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">save</span>
                                Update Document
                            </span>
                        </button>

                        <a href="{{ route('admin.tuk-documents.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Document Info</h3>
                        <dl class="text-xs text-gray-600 space-y-2">
                            @if($tukDocument->uploader)
                            <div>
                                <dt class="font-semibold">Uploaded By</dt>
                                <dd>{{ $tukDocument->uploader->name }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="font-semibold">Created</dt>
                                <dd>{{ $tukDocument->created_at->format('d M Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold">Last Updated</dt>
                                <dd>{{ $tukDocument->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
