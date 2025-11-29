@extends('layouts.admin')

@section('title', 'Assessment Results & Approval')

@php
    $active = 'assessment-results';
@endphp

@section('page_title', 'Assessment Results & Approval')
@section('page_description', 'View and manage assessment results and approvals')

@section('content')
    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">description</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total Results</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total_results'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600">hourglass_empty</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Pending Approval</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['pending_approval'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Approved</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['approved'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <span class="material-symbols-outlined text-purple-600">publish</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Published</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['published'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <span class="material-symbols-outlined text-orange-600">task_alt</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Pending Tasks</p>
                    <p class="text-xl font-bold text-orange-600">{{ $stats['pending_approvals'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('admin.assessment-results.index', ['tab' => 'results']) }}"
                   class="px-6 py-4 border-b-2 font-medium text-sm {{ $activeTab === 'results' ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span class="material-symbols-outlined align-middle text-lg mr-1">description</span>
                    Results
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $activeTab === 'results' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">{{ $results->total() }}</span>
                </a>
                <a href="{{ route('admin.assessment-results.index', ['tab' => 'approvals']) }}"
                   class="px-6 py-4 border-b-2 font-medium text-sm {{ $activeTab === 'approvals' ? 'border-blue-900 text-blue-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <span class="material-symbols-outlined align-middle text-lg mr-1">task_alt</span>
                    Approvals
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $activeTab === 'approvals' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">{{ $approvals->total() }}</span>
                    @if($stats['pending_approvals'] > 0)
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-orange-100 text-orange-700">{{ $stats['pending_approvals'] }} pending</span>
                    @endif
                </a>
            </nav>
        </div>

        @if($activeTab === 'results')
            <!-- Results Tab -->
            <div class="p-6 border-b border-gray-200">
                <form method="GET" action="{{ route('admin.assessment-results.index') }}" class="space-y-4">
                    <input type="hidden" name="tab" value="results">
                    <div class="flex flex-wrap gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search result number, certificate, assessee..."
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

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
                            <option value="requires_revision" {{ request('approval_status') === 'requires_revision' ? 'selected' : '' }}>Requires Revision</option>
                        </select>

                        <select name="is_published" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                            <option value="">All Publishing Status</option>
                            <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
                            <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Not Published</option>
                        </select>

                        <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                            Filter
                        </button>
                        <a href="{{ route('admin.assessment-results.index', ['tab' => 'results']) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                            Reset
                        </a>
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
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-300 mb-4" style="font-size: 80px;">description</span>
                                        <p class="text-gray-500 font-medium text-lg">No results found</p>
                                        <p class="text-gray-400 text-sm mt-1">Results will appear here after assessments are completed</p>
                                    </div>
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

        @else
            <!-- Approvals Tab -->
            <div class="p-6 border-b border-gray-200">
                <form method="GET" action="{{ route('admin.assessment-results.index') }}" class="space-y-4">
                    <input type="hidden" name="tab" value="approvals">
                    <div class="flex flex-wrap gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search result number, approver..."
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="returned_for_revision" {{ request('status') === 'returned_for_revision' ? 'selected' : '' }}>Returned for Revision</option>
                        </select>

                        <select name="decision" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                            <option value="">All Decisions</option>
                            <option value="approve" {{ request('decision') === 'approve' ? 'selected' : '' }}>Approve</option>
                            <option value="reject" {{ request('decision') === 'reject' ? 'selected' : '' }}>Reject</option>
                            <option value="request_revision" {{ request('decision') === 'request_revision' ? 'selected' : '' }}>Request Revision</option>
                        </select>

                        <select name="is_overdue" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                            <option value="">All</option>
                            <option value="1" {{ request('is_overdue') === '1' ? 'selected' : '' }}>Overdue Only</option>
                            <option value="0" {{ request('is_overdue') === '0' ? 'selected' : '' }}>Not Overdue</option>
                        </select>

                        <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                            Filter
                        </button>
                        <a href="{{ route('admin.assessment-results.index', ['tab' => 'approvals']) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Approval</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Result</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Approver</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Decision</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($approvals as $approval)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="material-symbols-outlined text-blue-900 text-3xl">task_alt</span>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $approval->approver_role)) }}</p>
                                            <p class="text-sm text-gray-600">Level {{ $approval->approval_level }}</p>
                                            @if($approval->sequence_order > 0)
                                                <p class="text-xs text-gray-500">Order: {{ $approval->sequence_order }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $approval->assessmentResult->result_number ?? '-' }}</p>
                                    <p class="text-sm text-gray-600">{{ $approval->assessmentResult->assessee->full_name ?? '-' }}</p>
                                    @if($approval->assessmentResult->certificate_number ?? null)
                                        <p class="text-xs text-gray-500 mt-1">{{ $approval->assessmentResult->certificate_number }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $approval->approver->name ?? '-' }}</p>
                                    @if($approval->delegated_to)
                                        <p class="text-sm text-blue-600">Delegated to: {{ $approval->delegatedTo->name ?? '-' }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                        Level {{ $approval->approval_level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-gray-100 text-gray-700',
                                            'in_review' => 'bg-yellow-100 text-yellow-700',
                                            'approved' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'returned_for_revision' => 'bg-orange-100 text-orange-700',
                                            'withdrawn' => 'bg-gray-100 text-gray-700',
                                        ];
                                        $statusColor = $statusColors[$approval->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ ucwords(str_replace('_', ' ', $approval->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($approval->decision)
                                        @php
                                            $decisionColors = [
                                                'approve' => 'bg-green-100 text-green-700',
                                                'reject' => 'bg-red-100 text-red-700',
                                                'request_revision' => 'bg-orange-100 text-orange-700',
                                                'defer' => 'bg-yellow-100 text-yellow-700',
                                            ];
                                            $decisionColor = $decisionColors[$approval->decision] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $decisionColor }}">
                                            {{ ucwords(str_replace('_', ' ', $approval->decision)) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($approval->due_date)
                                        <p class="text-sm {{ $approval->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                            {{ \Carbon\Carbon::parse($approval->due_date)->format('d M Y') }}
                                        </p>
                                        @if($approval->is_overdue)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 mt-1">
                                                Overdue
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.result-approval.show', $approval) }}"
                                            class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                            title="View Details">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </a>
                                        @if(in_array($approval->status, ['pending', 'in_review']))
                                            <a href="{{ route('admin.result-approval.edit', $approval) }}"
                                                class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition"
                                                title="Process">
                                                <span class="material-symbols-outlined">edit</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-gray-300 mb-4" style="font-size: 80px;">task_alt</span>
                                        <p class="text-gray-500 font-medium text-lg">No approvals found</p>
                                        <p class="text-gray-400 text-sm mt-1">Approvals will appear here when results are submitted for approval</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($approvals->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $approvals->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
