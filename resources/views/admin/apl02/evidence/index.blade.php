@extends('layouts.admin')

@section('title', 'APL-02 Evidence Management')

@php
    $active = 'apl02-evidence';
@endphp

@section('page_title', 'APL-02 Evidence Management')
@section('page_description', 'Manage APL-02 portfolio evidence and documentation')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Portfolio Evidence</h2>
                    <p class="text-sm text-gray-600">Total: {{ $evidence->total() }} evidence items</p>
                </div>
                <a href="{{ route('admin.apl02.evidence.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add Evidence</span>
                </a>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.apl02.evidence.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by evidence number or title..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'evidence_type', 'verification_status', 'assessment_result', 'unit_id']))
                        <a href="{{ route('admin.apl02.evidence.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <select name="evidence_type" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Evidence Types</option>
                        <option value="document" {{ request('evidence_type') === 'document' ? 'selected' : '' }}>Document</option>
                        <option value="certificate" {{ request('evidence_type') === 'certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="work_sample" {{ request('evidence_type') === 'work_sample' ? 'selected' : '' }}>Work Sample</option>
                        <option value="project" {{ request('evidence_type') === 'project' ? 'selected' : '' }}>Project</option>
                        <option value="photo" {{ request('evidence_type') === 'photo' ? 'selected' : '' }}>Photo</option>
                        <option value="video" {{ request('evidence_type') === 'video' ? 'selected' : '' }}>Video</option>
                        <option value="presentation" {{ request('evidence_type') === 'presentation' ? 'selected' : '' }}>Presentation</option>
                        <option value="log_book" {{ request('evidence_type') === 'log_book' ? 'selected' : '' }}>Log Book</option>
                        <option value="portfolio" {{ request('evidence_type') === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                        <option value="other" {{ request('evidence_type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>

                    <select name="verification_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Verification Status</option>
                        <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('verification_status') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="requires_clarification" {{ request('verification_status') === 'requires_clarification' ? 'selected' : '' }}>Requires Clarification</option>
                    </select>

                    <select name="assessment_result" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Assessment Results</option>
                        <option value="pending" {{ request('assessment_result') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="valid" {{ request('assessment_result') === 'valid' ? 'selected' : '' }}>Valid</option>
                        <option value="invalid" {{ request('assessment_result') === 'invalid' ? 'selected' : '' }}>Invalid</option>
                        <option value="insufficient" {{ request('assessment_result') === 'insufficient' ? 'selected' : '' }}>Insufficient</option>
                    </select>

                    <select name="unit_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Units</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->unit_code }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Evidence</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Verification</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($evidence as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-600 text-2xl">description</span>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $item->evidence_number }}</div>
                                        <div class="text-xs text-gray-600">{{ Str::limit($item->title, 40) }}</div>
                                        @if($item->file_name)
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span class="material-symbols-outlined text-xs">attach_file</span>
                                                {{ $item->file_name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $item->assessee->full_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $item->apl02Unit->unit_code }}</div>
                                <div class="text-xs text-gray-600">{{ Str::limit($item->apl02Unit->unit_title, 30) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'document' => 'bg-blue-100 text-blue-800',
                                        'certificate' => 'bg-purple-100 text-purple-800',
                                        'work_sample' => 'bg-green-100 text-green-800',
                                        'project' => 'bg-orange-100 text-orange-800',
                                        'photo' => 'bg-pink-100 text-pink-800',
                                        'video' => 'bg-red-100 text-red-800',
                                        'presentation' => 'bg-indigo-100 text-indigo-800',
                                        'log_book' => 'bg-yellow-100 text-yellow-800',
                                        'portfolio' => 'bg-teal-100 text-teal-800',
                                        'other' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $typeColors[$item->evidence_type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $item->evidence_type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $verificationColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'verified' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'requires_clarification' => 'bg-orange-100 text-orange-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $verificationColors[$item->verification_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $item->verification_status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $assessmentColors = [
                                        'pending' => 'bg-gray-100 text-gray-800',
                                        'valid' => 'bg-green-100 text-green-800',
                                        'invalid' => 'bg-red-100 text-red-800',
                                        'insufficient' => 'bg-orange-100 text-orange-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $assessmentColors[$item->assessment_result] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $item->assessment_result_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.apl02.evidence.show', $item) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                    @if($item->file_path)
                                        <a href="{{ route('admin.apl02.evidence.download', $item) }}" class="text-green-600 hover:text-green-800" title="Download">
                                            <span class="material-symbols-outlined text-xl">download</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.apl02.evidence.edit', $item) }}" class="text-gray-600 hover:text-gray-800" title="Edit">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                    <form action="{{ route('admin.apl02.evidence.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this evidence?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">folder_open</span>
                                <p class="font-medium">No evidence found</p>
                                <p class="text-sm">Try adjusting your search or filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($evidence->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $evidence->links() }}
            </div>
        @endif
    </div>
@endsection
