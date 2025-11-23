@extends('layouts.admin')

@section('title', 'Assessment Results')

@php
    $active = 'assessment-results';
@endphp

@section('page_title', 'Assessment Results')
@section('page_description', 'View and manage assessment results and certifications')

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
                    <h2 class="text-lg font-bold text-gray-900">Results List</h2>
                    <p class="text-sm text-gray-600">Total: {{ $results->total() }} results</p>
                </div>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.assessment-results.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') ?? '' }}" placeholder="Search by result number, certificate number, or assessee name..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'final_result', 'approval_status', 'is_published']))
                        <a href="{{ route('admin.assessment-results.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-3 gap-2">
                    <select name="final_result" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Results</option>
                        <option value="competent" {{ request('final_result') === 'competent' ? 'selected' : '' }}>Competent</option>
                        <option value="not_yet_competent" {{ request('final_result') === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                        <option value="requires_reassessment" {{ request('final_result') === 'requires_reassessment' ? 'selected' : '' }}>Requires Reassessment</option>
                    </select>

                    <select name="approval_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Approval Status</option>
                        <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="revision_required" {{ request('approval_status') === 'revision_required' ? 'selected' : '' }}>Revision Required</option>
                    </select>

                    <select name="is_published" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">All Publishing Status</option>
                        <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Not Published</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Result</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessee</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Scheme</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Final Result</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Approval</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Published</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($results as $result)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">description</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $result->result_number }}</p>
                                        @if($result->certificate_number)
                                            <p class="text-sm text-gray-600">{{ $result->certificate_number }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="font-medium">Assessor:</span> {{ $result->leadAssessor->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $result->assessee->full_name ?? '-' }}</p>
                                <p class="text-sm text-gray-600">{{ $result->assessee->assessee_number ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $result->scheme->name ?? '-' }}</p>
                                <p class="text-sm text-gray-600">{{ $result->scheme->code ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $resultColors = [
                                        'competent' => 'bg-green-100 text-green-700',
                                        'not_yet_competent' => 'bg-red-100 text-red-700',
                                        'requires_reassessment' => 'bg-orange-100 text-orange-700',
                                    ];
                                    $resultColor = $resultColors[$result->final_result] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $resultColor }}">
                                    {{ ucwords(str_replace('_', ' ', $result->final_result)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="font-bold text-gray-900">{{ number_format($result->overall_score, 1) }}%</p>
                                <p class="text-xs text-gray-500">{{ $result->criteria_met }}/{{ $result->total_criteria }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $approvalColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'revision_required' => 'bg-orange-100 text-orange-700',
                                    ];
                                    $approvalColor = $approvalColors[$result->approval_status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $approvalColor }}">
                                    {{ ucwords(str_replace('_', ' ', $result->approval_status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($result->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        Published
                                    </span>
                                    @if($result->certificate_issued)
                                        <p class="text-xs text-gray-500 mt-1">Certificate Issued</p>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assessment-results.show', $result) }}" class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition" title="View Details">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    @if($result->approval_status !== 'approved')
                                        <a href="{{ route('admin.assessment-results.edit', $result) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                    @endif
                                    @if($result->approval_status === 'pending')
                                        <form action="{{ route('admin.assessment-results.destroy', $result) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this result?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">description</span>
                                <p class="text-gray-500 font-medium">No results found</p>
                                <p class="text-sm text-gray-400 mt-2">Results will appear here after assessments are completed</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($results->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $results->links() }}
            </div>
        @endif
    </div>
@endsection
