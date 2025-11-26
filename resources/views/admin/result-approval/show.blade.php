@extends('layouts.admin')

@section('title', 'Approval Details')

@php
    $active = 'result-approval';
@endphp

@section('page_title', 'Approval Details')
@section('page_description', 'Review and process assessment result approval')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

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
                        <!-- Approve Button -->
                        <button type="button" onclick="openDecisionModal('approve')"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            <span>Approve</span>
                        </button>

                        <!-- Reject Button -->
                        <button type="button" onclick="openDecisionModal('reject')"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                            <span class="material-symbols-outlined text-sm">cancel</span>
                            <span>Reject</span>
                        </button>

                        <!-- Request Revision Button -->
                        <button type="button" onclick="openDecisionModal('request_revision')"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition font-medium">
                            <span class="material-symbols-outlined text-sm">edit_note</span>
                            <span>Request Revision</span>
                        </button>

                        <hr class="border-gray-200">

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

    <!-- Decision Modal -->
    @if(in_array($resultApproval->status, ['pending', 'in_review']))
        <div id="decisionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40" onclick="closeDecisionModal()"></div>

                <!-- Modal panel -->
                <div class="relative z-50 inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form id="decisionForm" action="{{ route('admin.result-approval.process-decision', $resultApproval) }}" method="POST">
                        @csrf
                        <input type="hidden" name="decision" id="decisionInput">

                        <div class="bg-white px-6 pt-6 pb-4">
                            <!-- Modal Header -->
                            <div class="flex items-center gap-3 mb-6">
                                <div id="modalIconApprove" class="hidden w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
                                </div>
                                <div id="modalIconReject" class="hidden w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-red-600 text-2xl">cancel</span>
                                </div>
                                <div id="modalIconRevision" class="hidden w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-orange-600 text-2xl">edit_note</span>
                                </div>
                                <div>
                                    <h3 id="modalTitle" class="text-lg font-bold text-gray-900"></h3>
                                    <p id="modalSubtitle" class="text-sm text-gray-600"></p>
                                </div>
                            </div>

                            <!-- Form Fields -->
                            <div class="space-y-4">
                                <!-- Comments (Required) -->
                                <div>
                                    <label for="comments" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Comments <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="comments" name="comments" rows="4" required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                        placeholder="Berikan komentar atau alasan untuk keputusan ini..."></textarea>
                                </div>

                                <!-- Recommendations (Optional) -->
                                <div>
                                    <label for="recommendations" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Recommendations
                                    </label>
                                    <textarea id="recommendations" name="recommendations" rows="3"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                        placeholder="Rekomendasi untuk tindak lanjut (opsional)..."></textarea>
                                </div>

                                <!-- Conditions (for Approve with conditions) -->
                                <div id="conditionsField" class="hidden">
                                    <label for="conditions" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Conditions
                                    </label>
                                    <textarea id="conditions" name="conditions" rows="2"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                        placeholder="Syarat/kondisi yang harus dipenuhi (opsional)..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="submit" id="submitBtn"
                                class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition">
                                Confirm
                            </button>
                            <button type="button" onclick="closeDecisionModal()"
                                class="w-full sm:w-auto px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openDecisionModal(decision) {
            const modal = document.getElementById('decisionModal');
            const decisionInput = document.getElementById('decisionInput');
            const modalTitle = document.getElementById('modalTitle');
            const modalSubtitle = document.getElementById('modalSubtitle');
            const submitBtn = document.getElementById('submitBtn');
            const conditionsField = document.getElementById('conditionsField');

            // Hide all icons first
            document.getElementById('modalIconApprove').classList.add('hidden');
            document.getElementById('modalIconReject').classList.add('hidden');
            document.getElementById('modalIconRevision').classList.add('hidden');

            // Reset button classes
            submitBtn.className = 'w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition';

            decisionInput.value = decision;

            if (decision === 'approve') {
                document.getElementById('modalIconApprove').classList.remove('hidden');
                document.getElementById('modalIconApprove').classList.add('flex');
                modalTitle.textContent = 'Approve Result';
                modalSubtitle.textContent = 'Setujui hasil asesmen ini';
                submitBtn.textContent = 'Approve';
                submitBtn.classList.add('bg-green-600', 'text-white', 'hover:bg-green-700');
                conditionsField.classList.remove('hidden');
            } else if (decision === 'reject') {
                document.getElementById('modalIconReject').classList.remove('hidden');
                document.getElementById('modalIconReject').classList.add('flex');
                modalTitle.textContent = 'Reject Result';
                modalSubtitle.textContent = 'Tolak hasil asesmen ini';
                submitBtn.textContent = 'Reject';
                submitBtn.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
                conditionsField.classList.add('hidden');
            } else if (decision === 'request_revision') {
                document.getElementById('modalIconRevision').classList.remove('hidden');
                document.getElementById('modalIconRevision').classList.add('flex');
                modalTitle.textContent = 'Request Revision';
                modalSubtitle.textContent = 'Minta revisi hasil asesmen';
                submitBtn.textContent = 'Request Revision';
                submitBtn.classList.add('bg-orange-500', 'text-white', 'hover:bg-orange-600');
                conditionsField.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDecisionModal() {
            const modal = document.getElementById('decisionModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Reset form
            document.getElementById('decisionForm').reset();
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDecisionModal();
            }
        });
    </script>
@endsection
