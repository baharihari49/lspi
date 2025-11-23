@extends('layouts.admin')

@section('title', 'Edit Document')

@php
    $active = 'assessment-documents';
@endphp

@section('page_title', 'Edit Document')
@section('page_description', $assessmentDocument->document_number)

@section('content')
    <form action="{{ route('admin.assessment-documents.update', $assessmentDocument) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Document Information</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div><label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title *</label><input type="text" id="title" name="title" value="{{ old('title', $assessmentDocument->title) }}" required class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div><label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label><textarea id="description" name="description" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $assessmentDocument->description) }}</textarea></div>
                        <div><label for="document_type" class="block text-sm font-semibold text-gray-700 mb-2">Document Type *</label>
                            <select id="document_type" name="document_type" required class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="evidence" {{ $assessmentDocument->document_type === 'evidence' ? 'selected' : '' }}>Evidence</option>
                                <option value="certificate" {{ $assessmentDocument->document_type === 'certificate' ? 'selected' : '' }}>Certificate</option>
                                <option value="report" {{ $assessmentDocument->document_type === 'report' ? 'selected' : '' }}>Report</option>
                                <option value="photo" {{ $assessmentDocument->document_type === 'photo' ? 'selected' : '' }}>Photo</option>
                                <option value="video" {{ $assessmentDocument->document_type === 'video' ? 'selected' : '' }}>Video</option>
                                <option value="other" {{ $assessmentDocument->document_type === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div><label for="evidence_type" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Type</label>
                            <select id="evidence_type" name="evidence_type" class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">Not specified</option>
                                <option value="direct" {{ $assessmentDocument->evidence_type === 'direct' ? 'selected' : '' }}>Direct</option>
                                <option value="indirect" {{ $assessmentDocument->evidence_type === 'indirect' ? 'selected' : '' }}>Indirect</option>
                                <option value="supplementary" {{ $assessmentDocument->evidence_type === 'supplementary' ? 'selected' : '' }}>Supplementary</option>
                                <option value="historical" {{ $assessmentDocument->evidence_type === 'historical' ? 'selected' : '' }}>Historical</option>
                            </select>
                        </div>
                        <div><label for="file" class="block text-sm font-semibold text-gray-700 mb-2">Replace File (Optional)</label><input type="file" id="file" name="file" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"><p class="mt-1 text-sm text-gray-600">Current: {{ $assessmentDocument->original_filename }} ({{ $assessmentDocument->getFileSizeFormatted() }})</p></div>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all">Update Document</button>
                        <a href="{{ route('admin.assessment-documents.show', $assessmentDocument) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
