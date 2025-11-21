@extends('layouts.admin')

@section('title', 'Edit Document Type')

@php
    $active = 'master-data';
@endphp

@section('page_title', 'Edit Document Type')
@section('page_description', 'Update document type information')

@section('content')
    <form action="{{ route('admin.master-document-types.update', $masterDocumentType) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Document Type Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Document Type Information</h3>

                    <!-- Code -->
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Code *</label>
                        <input type="text" id="code" name="code" value="{{ old('code', $masterDocumentType->code) }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('code') border-red-500 @enderror"
                            placeholder="e.g. ktp, ijazah, sertifikat">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Unique code for document type (lowercase with hyphens)</p>
                    </div>

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $masterDocumentType->name) }}" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('name') border-red-500 @enderror"
                            placeholder="e.g. KTP, Ijazah, Sertifikat Kompetensi">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('description') border-red-500 @enderror">{{ old('description', $masterDocumentType->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Retention Period -->
                    <div>
                        <label for="retention_months" class="block text-sm font-semibold text-gray-700 mb-2">Retention Period (Months)</label>
                        <input type="number" id="retention_months" name="retention_months" value="{{ old('retention_months', $masterDocumentType->retention_months) }}" min="0"
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('retention_months') border-red-500 @enderror"
                            placeholder="e.g. 12, 24, 60">
                        @error('retention_months')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">How long should this document be retained? Leave empty for permanent retention.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Update Document Type</span>
                        </button>
                        <a href="{{ route('admin.master-document-types.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>

                <!-- Document Type Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Document Type Info</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created:</span>
                            <span class="font-semibold text-gray-900">{{ $masterDocumentType->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Updated:</span>
                            <span class="font-semibold text-gray-900">{{ $masterDocumentType->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Guide -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Retention Examples</h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• <strong>12 months</strong> - Temporary documents</li>
                        <li>• <strong>24 months</strong> - Short-term records</li>
                        <li>• <strong>60 months</strong> - Long-term records</li>
                        <li>• <strong>Empty</strong> - Permanent retention</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
@endsection
