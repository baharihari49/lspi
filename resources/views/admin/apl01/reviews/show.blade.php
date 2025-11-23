@extends('layouts.admin')

@section('title', 'Review Details')

@php
    $active = 'apl01-reviews';
@endphp

@section('page_title', 'Review Details')
@section('page_description', $review->form->form_number . ' - ' . $review->review_level_name)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Review Information</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">description</span>
                            <div>
                                <p class="text-xs text-gray-600">Form Number</p>
                                <a href="{{ route('admin.apl01.show', $review->form) }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                    {{ $review->form->form_number }}
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">layers</span>
                            <div>
                                <p class="text-xs text-gray-600">Review Level</p>
                                <p class="font-semibold text-gray-900">{{ $review->review_level_name ?? 'Level ' . $review->review_level }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">person</span>
                            <div>
                                <p class="text-xs text-gray-600">Reviewer</p>
                                <p class="font-semibold text-gray-900">{{ $review->reviewer->name }}</p>
                                @if($review->reviewer_role)
                                    <p class="text-xs text-gray-500">{{ $review->reviewer_role }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600">verified</span>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Decision</p>
                                @php
                                    $decisionColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'approved_with_notes' => 'bg-blue-100 text-blue-800',
                                        'returned' => 'bg-orange-100 text-orange-800',
                                        'forwarded' => 'bg-purple-100 text-purple-800',
                                        'on_hold' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $decisionColors[$review->decision] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{-- {{ $review->decision_label }} --}}
                                </span>
                            </div>
                        </div>

                        @if($review->score)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-blue-600">grade</span>
                                <div>
                                    <p class="text-xs text-gray-600">Score</p>
                                    <p class="font-semibold text-gray-900">{{ $review->score }}</p>
                                </div>
                            </div>
                        @endif

                        @if($review->deadline)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-{{ $review->is_overdue && !$review->completed_at ? 'red' : 'gray' }}-600">schedule</span>
                                <div>
                                    <p class="text-xs text-gray-600">Deadline</p>
                                    <p class="font-semibold text-gray-900">{{ $review->deadline->format('d M Y H:i') }}</p>
                                    @if($review->is_overdue && !$review->completed_at)
                                        <p class="text-xs text-red-600 font-semibold">Overdue!</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($review->review_notes)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Review Notes</p>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $review->review_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($review->rejection_reason)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Rejection Reason</p>
                            <div class="bg-red-50 rounded-lg p-4">
                                <p class="text-sm text-red-800 whitespace-pre-wrap">{{ $review->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif

                    @if($review->field_feedback)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Field-Specific Feedback</p>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($review->field_feedback, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assessee & Form Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessee Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">person</span>
                        <div>
                            <p class="text-xs text-gray-600">Full Name</p>
                            <p class="font-semibold text-gray-900">{{ $review->form->assessee->full_name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">mail</span>
                        <div>
                            <p class="text-xs text-gray-600">Email</p>
                            <p class="font-semibold text-gray-900">{{ $review->form->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">workspace_premium</span>
                        <div>
                            <p class="text-xs text-gray-600">Scheme</p>
                            <p class="font-semibold text-gray-900">{{ $review->form->scheme->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">pending_actions</span>
                        <div>
                            <p class="text-xs text-gray-600">Form Status</p>
                            <p class="font-semibold text-gray-900">{{ $review->form->status_label }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Escalation Information -->
            @if($review->is_escalated)
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-orange-600">warning</span>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-orange-900 mb-2">Escalated Review</h3>
                            @if($review->escalation_reason)
                                <p class="text-sm text-orange-800 mb-2"><strong>Reason:</strong> {{ $review->escalation_reason }}</p>
                            @endif
                            @if($review->escalatedTo)
                                <p class="text-sm text-orange-800"><strong>Escalated to:</strong> {{ $review->escalatedTo->name }}</p>
                            @endif
                            @if($review->escalated_at)
                                <p class="text-xs text-orange-600 mt-1">{{ $review->escalated_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Review Timeline</h3>

                <div class="space-y-3">
                    @if($review->assigned_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">assignment</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Assigned</p>
                                <p class="text-xs text-gray-500">{{ $review->assigned_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($review->started_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">play_arrow</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Started</p>
                                <p class="text-xs text-gray-500">{{ $review->started_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($review->completed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Completed</p>
                                <p class="text-xs text-gray-500">{{ $review->completed_at->format('d M Y H:i') }}</p>
                                @if($review->review_duration)
                                    <p class="text-xs text-gray-500">Duration: {{ $review->review_duration }} minutes</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    @if($review->decision === 'pending' && !$review->completed_at)
                        <a href="{{ route('admin.apl01-reviews.review', $review) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                            Conduct Review
                        </a>
                    @endif

                    <a href="{{ route('admin.apl01.show', $review->form) }}" class="block w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-center leading-[3rem] transition-all">
                        View Form
                    </a>

                    <a href="{{ route('admin.apl01-reviews.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Created:</span><br>
                        {{ $review->created_at->format('d M Y H:i') }}
                    </p>
                    @if($review->updated_at != $review->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Last Updated:</span><br>
                            {{ $review->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Review Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Review Status</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Current</span>
                        @if($review->is_current)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Final Review</span>
                        @if($review->is_final)
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Escalated</span>
                        @if($review->is_escalated)
                            <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>
                    @if($review->completed_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Completed</span>
                        </div>
                    @elseif($review->started_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">In Progress</span>
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Not Started</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
