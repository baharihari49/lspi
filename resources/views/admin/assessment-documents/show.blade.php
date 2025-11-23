@extends('layouts.admin')

@section('title', 'Document Details')

@php
    $active = 'assessment-documents';
@endphp

@section('page_title', $assessmentDocument->document_number)
@section('page_description', $assessmentDocument->title)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Document Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Document Number</label><p class="text-gray-900">{{ $assessmentDocument->document_number }}</p></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Document Type</label><span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-purple-100 text-purple-800">{{ ucfirst($assessmentDocument->document_type) }}</span></div>
                    <div class="md:col-span-2"><label class="block text-sm font-semibold text-gray-700 mb-1">Title</label><p class="text-gray-900">{{ $assessmentDocument->title }}</p></div>
                    @if($assessmentDocument->description)<div class="md:col-span-2"><label class="block text-sm font-semibold text-gray-700 mb-1">Description</label><p class="text-gray-900">{{ $assessmentDocument->description }}</p></div>@endif
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Assessment</label><a href="{{ route('admin.assessments.show', $assessmentDocument->assessment) }}" class="text-blue-900 hover:underline">{{ $assessmentDocument->assessment->assessment_number }}</a></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Assessee</label><p class="text-gray-900">{{ $assessmentDocument->assessment->assessee->full_name }}</p></div>
                    @if($assessmentDocument->assessmentUnit)<div><label class="block text-sm font-semibold text-gray-700 mb-1">Unit</label><a href="{{ route('admin.assessment-units.show', $assessmentDocument->assessmentUnit) }}" class="text-blue-900 hover:underline">{{ $assessmentDocument->assessmentUnit->unit_code }}</a></div>@endif
                    @if($assessmentDocument->evidence_type)<div><label class="block text-sm font-semibold text-gray-700 mb-1">Evidence Type</label><p class="text-gray-900">{{ ucfirst($assessmentDocument->evidence_type) }}</p></div>@endif
                </div>
            </div>

            <!-- File Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">File Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Original Filename</label><p class="text-gray-900">{{ $assessmentDocument->original_filename }}</p></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">File Size</label><p class="text-gray-900">{{ $assessmentDocument->getFileSizeFormatted() }}</p></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">File Type</label><p class="text-gray-900">{{ $assessmentDocument->file_type }}</p></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Uploaded By</label><p class="text-gray-900">{{ $assessmentDocument->uploader->name ?? '-' }}</p></div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Uploaded At</label><p class="text-gray-900">{{ $assessmentDocument->created_at->format('d M Y H:i') }}</p></div>
                </div>
            </div>

            <!-- Verification -->
            @if($assessmentDocument->verification_status === 'pending')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Verify Document</h3>
                <form action="{{ route('admin.assessment-documents.verify', $assessmentDocument) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="verification_status" class="block text-sm font-semibold text-gray-700 mb-2">Verification Status *</label>
                            <select id="verification_status" name="verification_status" required class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">Select Status</option>
                                <option value="verified">Verified</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label for="relevance" class="block text-sm font-semibold text-gray-700 mb-2">Relevance *</label>
                            <select id="relevance" name="relevance" required class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="highly_relevant">Highly Relevant</option>
                                <option value="relevant">Relevant</option>
                                <option value="partially_relevant">Partially Relevant</option>
                                <option value="not_relevant">Not Relevant</option>
                            </select>
                        </div>
                        <div>
                            <label for="verification_notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea id="verification_notes" name="verification_notes" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                        </div>
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all">Submit Verification</button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Verification Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        @php $statusColors = ['verified' => 'bg-green-100 text-green-800', 'rejected' => 'bg-red-100 text-red-800']; @endphp
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $statusColors[$assessmentDocument->verification_status] }}">{{ ucfirst($assessmentDocument->verification_status) }}</span>
                    </div>
                    @if($assessmentDocument->relevance)<div><label class="block text-sm font-semibold text-gray-700 mb-1">Relevance</label><p class="text-gray-900">{{ ucwords(str_replace('_', ' ', $assessmentDocument->relevance)) }}</p></div>@endif
                    @if($assessmentDocument->verifier)<div><label class="block text-sm font-semibold text-gray-700 mb-1">Verified By</label><p class="text-gray-900">{{ $assessmentDocument->verifier->name }}</p></div>@endif
                    @if($assessmentDocument->verified_at)<div><label class="block text-sm font-semibold text-gray-700 mb-1">Verified At</label><p class="text-gray-900">{{ $assessmentDocument->verified_at->format('d M Y H:i') }}</p></div>@endif
                    @if($assessmentDocument->verification_notes)<div class="md:col-span-2"><label class="block text-sm font-semibold text-gray-700 mb-1">Notes</label><div class="bg-gray-50 rounded-lg p-4"><p class="text-gray-900">{{ $assessmentDocument->verification_notes }}</p></div></div>@endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.assessment-documents.download', $assessmentDocument) }}" class="block w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-center leading-[3rem] transition-all">Download File</a>
                    <a href="{{ route('admin.assessment-documents.edit', $assessmentDocument) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">Edit Document</a>
                    <a href="{{ route('admin.assessments.show', $assessmentDocument->assessment) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">View Assessment</a>
                    <form action="{{ route('admin.assessment-documents.destroy', $assessmentDocument) }}" method="POST" onsubmit="return confirm('Delete this document?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all">Delete Document</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
