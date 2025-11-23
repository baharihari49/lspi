@extends('layouts.admin')

@section('title', 'Approval Details')

@php
    $active = 'result-approval';
@endphp

@section('page_title', 'Approval Details')
@section('page_description', 'Review and process assessment result approval')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Approval Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Approval Information</h3>

                <div class="flex items-start gap-4 mb-6">
                    <span class="material-symbols-outlined text-blue-900 text-4xl">task_alt</span>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-900">{{ ucwords(str_replace('_', ' ', $resultApproval->approver_role)) }}</h4>
                        <p class="text-sm text-gray-600">Approval Level {{ $resultApproval->approval_level }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-gray-100 text-gray-700',
                                    'in_review' => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'returned_for_revision' => 'bg-orange-100 text-orange-700',
                                    'withdrawn' => 'bg-gray-100 text-gray-700',
                                ];
                                $decisionColors = [
                                    'approve' => 'bg-green-100 text-green-700',
                                    'reject' => 'bg-red-100 text-red-700',
                                    'request_revision' => 'bg-orange-100 text-orange-700',
                                    'defer' => 'bg-yellow-100 text-yellow-700',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 {{ $statusColors[$resultApproval->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                {{ ucwords(str_replace('_', ' ', $resultApproval->status)) }}
                            </span>
                            @if($resultApproval->decision)
                                <span class="px-2.5 py-0.5 {{ $decisionColors[$resultApproval->decision] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ ucwords(str_replace('_', ' ', $resultApproval->decision)) }}
                                </span>
                            @endif
                            @if($resultApproval->is_overdue)
                                <span class="px-2.5 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Overdue</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">person</span>
                        <div>
                            <p class="text-xs text-gray-600">Approver</p>
                            <p class="font-semibold text-gray-900">{{ $resultApproval->approver->name }}</p>
                            <p class="text-xs text-gray-500">{{ $resultApproval->approver->email }}</p>
                        </div>
                    </div>

                    @if($resultApproval->delegated_to)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-400">forward</span>
                            <div>
                                <p class="text-xs text-gray-600">Delegated To</p>
                                <p class="font-semibold text-blue-900">{{ $resultApproval->delegatedTo->name }}</p>
                                <p class="text-xs text-blue-500">{{ $resultApproval->delegatedTo->email }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">schedule</span>
                        <div>
                            <p class="text-xs text-gray-600">Assigned Date</p>
                            <p class="font-semibold text-gray-900">{{ $resultApproval->assigned_at ? $resultApproval->assigned_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>

                    @if($resultApproval->due_date)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ $resultApproval->is_overdue ? 'text-red-400' : 'text-gray-400' }}">event</span>
                            <div>
                                <p class="text-xs text-gray-600">Due Date</p>
                                <p class="font-semibold {{ $resultApproval->is_overdue ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ \Carbon\Carbon::parse($resultApproval->due_date)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->reviewed_at)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">check_circle</span>
                            <div>
                                <p class="text-xs text-gray-600">Reviewed Date</p>
                                <p class="font-semibold text-gray-900">{{ $resultApproval->reviewed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->decision_at)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">done_all</span>
                            <div>
                                <p class="text-xs text-gray-600">Decision Date</p>
                                <p class="font-semibold text-gray-900">{{ $resultApproval->decision_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->review_duration_minutes)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">timer</span>
                            <div>
                                <p class="text-xs text-gray-600">Review Duration</p>
                                <p class="font-semibold text-gray-900">
                                    @if($resultApproval->review_duration_minutes < 60)
                                        {{ $resultApproval->review_duration_minutes }} minutes
                                    @else
                                        {{ round($resultApproval->review_duration_minutes / 60, 1) }} hours
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->sequence_order > 0)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">format_list_numbered</span>
                            <div>
                                <p class="text-xs text-gray-600">Sequence Order</p>
                                <p class="font-semibold text-gray-900">{{ $resultApproval->sequence_order }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assessment Result Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Result</h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-blue-900 text-4xl">description</span>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $resultApproval->assessmentResult->result_number }}</h4>
                                    @if($resultApproval->assessmentResult->certificate_number)
                                        <p class="text-sm text-gray-600">{{ $resultApproval->assessmentResult->certificate_number }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('admin.assessment-results.show', $resultApproval->assessmentResult) }}"
                                    class="px-3 py-1.5 bg-blue-50 text-blue-900 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                    View Result
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600">Assessee</p>
                            <p class="font-semibold text-gray-900">{{ $resultApproval->assessmentResult->assessee->full_name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $resultApproval->assessmentResult->assessee->assessee_number ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600">Scheme</p>
                            <p class="font-semibold text-gray-900">{{ $resultApproval->assessmentResult->scheme->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $resultApproval->assessmentResult->scheme->code ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600">Final Result</p>
                            @php
                                $resultColors = [
                                    'competent' => 'bg-green-100 text-green-700',
                                    'not_yet_competent' => 'bg-red-100 text-red-700',
                                    'requires_reassessment' => 'bg-orange-100 text-orange-700',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $resultColors[$resultApproval->assessmentResult->final_result] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucwords(str_replace('_', ' ', $resultApproval->assessmentResult->final_result)) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs text-gray-600">Overall Score</p>
                            <p class="font-bold text-gray-900">{{ number_format($resultApproval->assessmentResult->overall_score, 1) }}%</p>
                            <p class="text-xs text-gray-500">{{ $resultApproval->assessmentResult->criteria_met }}/{{ $resultApproval->assessmentResult->total_criteria }} criteria met</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments and Feedback -->
            @if($resultApproval->comments || $resultApproval->recommendations)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Comments & Recommendations</h3>

                    @if($resultApproval->comments)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Comments</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $resultApproval->comments }}</p>
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->recommendations)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Recommendations</h4>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $resultApproval->recommendations }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Checklist -->
            @if($resultApproval->checklist && count($resultApproval->checklist) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Approval Checklist</h3>

                    <div class="space-y-3">
                        @foreach($resultApproval->checklist as $item)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <span class="material-symbols-outlined {{ $item['checked'] ?? false ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $item['checked'] ?? false ? 'check_box' : 'check_box_outline_blank' }}
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $item['item'] }}</p>
                                    @if(isset($item['notes']) && $item['notes'])
                                        <p class="text-xs text-gray-600 mt-1">{{ $item['notes'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Issues and Required Changes -->
            @if(($resultApproval->issues_identified && count($resultApproval->issues_identified) > 0) || ($resultApproval->required_changes && count($resultApproval->required_changes) > 0))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Issues & Required Changes</h3>

                    @if($resultApproval->issues_identified && count($resultApproval->issues_identified) > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Issues Identified</h4>
                            <div class="space-y-2">
                                @foreach($resultApproval->issues_identified as $issue)
                                    <div class="flex items-start gap-2 bg-red-50 p-3 rounded-lg">
                                        <span class="material-symbols-outlined text-red-600 text-sm">warning</span>
                                        <p class="text-sm text-gray-900">{{ $issue }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->required_changes && count($resultApproval->required_changes) > 0)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Required Changes</h4>
                            <div class="space-y-2">
                                @foreach($resultApproval->required_changes as $change)
                                    <div class="flex items-start gap-2 bg-orange-50 p-3 rounded-lg">
                                        <span class="material-symbols-outlined text-orange-600 text-sm">edit_note</span>
                                        <p class="text-sm text-gray-900">{{ $change }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Conditions -->
            @if($resultApproval->conditions)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Approval Conditions</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $resultApproval->conditions }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    @if(in_array($resultApproval->status, ['pending', 'in_review']))
                        <a href="{{ route('admin.result-approval.edit', $resultApproval) }}"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit Approval</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.assessment-results.show', $resultApproval->assessmentResult) }}"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        <span class="material-symbols-outlined text-sm">description</span>
                        <span>View Result</span>
                    </a>

                    <a href="{{ route('admin.result-approval.index') }}"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        <span>Back to List</span>
                    </a>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>

                <div class="space-y-4">
                    @if($resultApproval->decision_at)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <div class="w-0.5 h-full bg-gray-200"></div>
                            </div>
                            <div class="pb-4">
                                <p class="text-sm font-semibold text-gray-900">Decision Made</p>
                                <p class="text-xs text-gray-500">{{ $resultApproval->decision_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->reviewed_at)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <div class="w-0.5 h-full bg-gray-200"></div>
                            </div>
                            <div class="pb-4">
                                <p class="text-sm font-semibold text-gray-900">Reviewed</p>
                                <p class="text-xs text-gray-500">{{ $resultApproval->reviewed_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->delegated_at)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                                <div class="w-0.5 h-full bg-gray-200"></div>
                            </div>
                            <div class="pb-4">
                                <p class="text-sm font-semibold text-gray-900">Delegated</p>
                                <p class="text-xs text-gray-500">{{ $resultApproval->delegated_at->format('d M Y H:i') }}</p>
                                @if($resultApproval->delegatedTo)
                                    <p class="text-xs text-gray-600 mt-1">To: {{ $resultApproval->delegatedTo->name }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($resultApproval->assigned_at)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Assigned</p>
                                <p class="text-xs text-gray-500">{{ $resultApproval->assigned_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Delegation Info -->
            @if($resultApproval->delegated_to && $resultApproval->delegation_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Delegation Notes</h3>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $resultApproval->delegation_notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
