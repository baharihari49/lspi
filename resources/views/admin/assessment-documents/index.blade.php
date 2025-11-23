@extends('layouts.admin')

@section('title', 'Assessment Documents')

@php
    $active = 'assessment-documents';
@endphp

@section('page_title', 'Assessment Documents')
@section('page_description', 'Manage assessment evidence and supporting documents')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div></div>
        <a href="{{ route('admin.assessment-documents.create') }}" class="h-12 px-6 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">upload_file</span>
            Upload Document
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.assessment-documents.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or document number..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="verification_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('verification_status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <select name="document_type" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Types</option>
                    <option value="evidence" {{ request('document_type') === 'evidence' ? 'selected' : '' }}>Evidence</option>
                    <option value="certificate" {{ request('document_type') === 'certificate' ? 'selected' : '' }}>Certificate</option>
                    <option value="report" {{ request('document_type') === 'report' ? 'selected' : '' }}>Report</option>
                    <option value="photo" {{ request('document_type') === 'photo' ? 'selected' : '' }}>Photo</option>
                    <option value="video" {{ request('document_type') === 'video' ? 'selected' : '' }}>Video</option>
                    <option value="other" {{ request('document_type') === 'other' ? 'selected' : '' }}>Other</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.assessment-documents.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Documents List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessment</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File Info</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Uploaded</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">folder_open</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $doc->document_number }}</p>
                                        <p class="text-sm text-gray-600">{{ Str::limit($doc->title, 50) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $doc->assessment->assessment_number }}</p>
                                <p class="text-sm text-gray-600">{{ $doc->assessment->assessee->full_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                    {{ ucfirst($doc->document_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900 text-sm">{{ $doc->original_filename }}</p>
                                <p class="text-xs text-gray-500">{{ $doc->getFileSizeFormatted() }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'verified' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                    $statusColor = $statusColors[$doc->verification_status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst($doc->verification_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $doc->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $doc->uploader->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessment-documents.show', $doc) }}"
                                        class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                        title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.assessment-documents.download', $doc) }}"
                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                        title="Download">
                                        <span class="material-symbols-outlined">download</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 mb-4" style="font-size: 80px;">folder_open</span>
                                    <p class="text-gray-500 font-medium text-lg">No documents found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or upload a new document</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($documents->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $documents->links() }}</div>
        @endif
    </div>
@endsection
