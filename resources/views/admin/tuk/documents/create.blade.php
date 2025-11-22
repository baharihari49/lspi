@extends('layouts.admin')

@section('title', 'Add TUK Document')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add TUK Document</h1>
        <p class="text-gray-600 mt-2">Upload a new document for a TUK</p>
    </div>

    <form action="{{ route('admin.tuk-documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                                    <option value="{{ $tuk->id }}" {{ old('tuk_id') == $tuk->id ? 'selected' : '' }}>
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
                                    <option value="{{ $type->id }}" {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
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
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror" placeholder="e.g., TUK Safety Certificate 2025">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="Brief description of the document">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">Upload Document <span class="text-red-500">*</span></label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition @error('document_file') border-red-500 @enderror">
                                <div class="space-y-1 text-center">
                                    <span class="material-symbols-outlined text-4xl text-gray-400">upload_file</span>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="document_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="document_file" name="document_file" type="file" class="sr-only" required accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG up to 10MB</p>
                                </div>
                            </div>
                            @error('document_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p id="file-name" class="mt-2 text-sm text-gray-600"></p>
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
                                <input type="date" id="issued_date" name="issued_date" value="{{ old('issued_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('issued_date') border-red-500 @enderror">
                                @error('issued_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expiry_date') border-red-500 @enderror">
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
                                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
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
                                Save Document
                            </span>
                        </button>

                        <a href="{{ route('admin.tuk-documents.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Tips</h3>
                        <ul class="text-xs text-gray-600 space-y-2">
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span>Supported formats: PDF, Word, Excel, Images</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span>Maximum file size: 10MB</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-sm">info</span>
                                <span>Keep track of document expiry dates</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('document_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const fileSize = (e.target.files[0]?.size / 1024 / 1024).toFixed(2);
            if (fileName) {
                document.getElementById('file-name').textContent = `Selected: ${fileName} (${fileSize} MB)`;
            }
        });
    </script>
</div>
@endsection
