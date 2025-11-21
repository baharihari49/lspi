@extends('layouts.admin')

@section('title', 'Create Document Type')

@php
    $active = 'master-data';
@endphp

@section('page_title', 'Create New Document Type')
@section('page_description', 'Add a new document type')

@section('content')
    <form action="{{ route('admin.master-document-types.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Document Type Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Document Type Information</h3>

                    <!-- Code -->
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Code *</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required
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
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
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
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Retention Period -->
                    <div>
                        <label for="retention_months" class="block text-sm font-semibold text-gray-700 mb-2">Retention Period (Months)</label>
                        <input type="number" id="retention_months" name="retention_months" value="{{ old('retention_months') }}" min="0"
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
                            <span>Save Document Type</span>
                        </button>
                        <a href="{{ route('admin.master-document-types.index') }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>
                </div>

                <!-- Info Guide -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2">Document Type Examples</h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• <strong>KTP</strong> - ID card</li>
                        <li>• <strong>Ijazah</strong> - Diploma/certificate</li>
                        <li>• <strong>Sertifikat</strong> - Competency certificate</li>
                        <li>• <strong>CV</strong> - Curriculum vitae</li>
                        <li>• <strong>Portfolio</strong> - Work portfolio</li>
                        <li>• <strong>SKKNI</strong> - Indonesian National Work Competency Standard</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
@endsection
