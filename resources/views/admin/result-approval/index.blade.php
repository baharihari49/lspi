@extends('layouts.admin')

@section('title', 'Result Approval')

@php
    $active = 'result-approval';
@endphp

@section('page_title', 'Result Approval')
@section('page_description', 'Manage and process assessment result approvals')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.result-approval.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search result number, certificate, approver..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm flex-1 min-w-[200px]">

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="returned_for_revision" {{ request('status') === 'returned_for_revision' ? 'selected' : '' }}>Returned for Revision</option>
                    <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                </select>

                <select name="decision" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Decisions</option>
                    <option value="approve" {{ request('decision') === 'approve' ? 'selected' : '' }}>Approve</option>
                    <option value="reject" {{ request('decision') === 'reject' ? 'selected' : '' }}>Reject</option>
                    <option value="request_revision" {{ request('decision') === 'request_revision' ? 'selected' : '' }}>Request Revision</option>
                    <option value="defer" {{ request('decision') === 'defer' ? 'selected' : '' }}>Defer</option>
                </select>

                <select name="approver_role" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Roles</option>
                    <option value="lead_assessor" {{ request('approver_role') === 'lead_assessor' ? 'selected' : '' }}>Lead Assessor</option>
                    <option value="technical_reviewer" {{ request('approver_role') === 'technical_reviewer' ? 'selected' : '' }}>Technical Reviewer</option>
                    <option value="quality_assurance" {{ request('approver_role') === 'quality_assurance' ? 'selected' : '' }}>Quality Assurance</option>
                    <option value="certification_manager" {{ request('approver_role') === 'certification_manager' ? 'selected' : '' }}>Certification Manager</option>
                    <option value="director" {{ request('approver_role') === 'director' ? 'selected' : '' }}>Director</option>
                    <option value="external_verifier" {{ request('approver_role') === 'external_verifier' ? 'selected' : '' }}>External Verifier</option>
                </select>

                <select name="approval_level" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All Levels</option>
                    <option value="1" {{ request('approval_level') === '1' ? 'selected' : '' }}>Level 1</option>
                    <option value="2" {{ request('approval_level') === '2' ? 'selected' : '' }}>Level 2</option>
                    <option value="3" {{ request('approval_level') === '3' ? 'selected' : '' }}>Level 3</option>
                    <option value="4" {{ request('approval_level') === '4' ? 'selected' : '' }}>Level 4</option>
                </select>

                <select name="is_overdue" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">All</option>
                    <option value="1" {{ request('is_overdue') === '1' ? 'selected' : '' }}>Overdue Only</option>
                    <option value="0" {{ request('is_overdue') === '0' ? 'selected' : '' }}>Not Overdue</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('admin.result-approval.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Approvals List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
                                <p class="font-medium text-gray-900">{{ $approval->assessmentResult->result_number }}</p>
                                <p class="text-sm text-gray-600">{{ $approval->assessmentResult->assessee->full_name ?? '-' }}</p>
                                @if($approval->assessmentResult->certificate_number)
                                    <p class="text-xs text-gray-500 mt-1">{{ $approval->assessmentResult->certificate_number }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $approval->approver->name }}</p>
                                @if($approval->delegated_to)
                                    <p class="text-sm text-blue-600">Delegated to: {{ $approval->delegatedTo->name }}</p>
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
                                            title="Edit">
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
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or create a new approval</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($approvals->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $approvals->links() }}
            </div>
        @endif
    </div>
@endsection
