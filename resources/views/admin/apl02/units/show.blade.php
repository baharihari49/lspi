@extends('layouts.admin')

@section('title', 'Unit Details - ' . $unit->unit_code)

@php
    $active = 'apl02-units';
@endphp

@section('page_title', 'Unit Details')
@section('page_description', $unit->unit_code . ' - ' . $unit->unit_title)

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Unit Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Unit Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">tag</span>
                        <div>
                            <p class="text-xs text-gray-600">Unit Code</p>
                            <p class="font-semibold text-gray-900">{{ $unit->unit_code }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">person</span>
                        <div>
                            <p class="text-xs text-gray-600">Assessee</p>
                            <p class="font-semibold text-gray-900">{{ $unit->assessee->full_name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                        <div>
                            <p class="text-xs text-gray-600">Scheme</p>
                            <p class="font-semibold text-gray-900">{{ $unit->scheme->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">verified</span>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Status</p>
                            @php
                                $statusColors = [
                                    'not_started' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'submitted' => 'bg-yellow-100 text-yellow-800',
                                    'under_review' => 'bg-purple-100 text-purple-800',
                                    'competent' => 'bg-green-100 text-green-800',
                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$unit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $unit->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">assessment</span>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Assessment Result</p>
                            @php
                                $resultColors = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'competent' => 'bg-green-100 text-green-800',
                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                    'requires_more_evidence' => 'bg-orange-100 text-orange-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $resultColors[$unit->assessment_result] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $unit->assessment_result_label }}
                            </span>
                        </div>
                    </div>

                    @if($unit->assessor)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">supervisor_account</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessor</p>
                                <p class="font-semibold text-gray-900">{{ $unit->assessor->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($unit->event)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">event</span>
                            <div>
                                <p class="text-xs text-gray-600">Event</p>
                                <p class="font-semibold text-gray-900">{{ $unit->event->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($unit->score)
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600">grade</span>
                            <div>
                                <p class="text-xs text-gray-600">Score</p>
                                <p class="font-semibold text-gray-900">{{ number_format($unit->score, 2) }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($unit->unit_description)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-600 mb-2">Description</p>
                        <p class="text-sm text-gray-700">{{ $unit->unit_description }}</p>
                    </div>
                @endif

                @if($unit->assessment_notes)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-600 mb-2">Assessment Notes</p>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $unit->assessment_notes }}</p>
                        </div>
                    </div>
                @endif

                @if($unit->recommendations)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <p class="text-xs text-gray-600 mb-2">Recommendations</p>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-blue-900 whitespace-pre-wrap">{{ $unit->recommendations }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Completion Progress</h3>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Overall Completion</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($unit->completion_percentage, 2) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $unit->completion_percentage }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-900">{{ $unit->total_evidence }}</p>
                            <p class="text-xs text-blue-700 mt-1">Total Evidence</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-2xl font-bold text-purple-900">{{ $unit->schemeUnit->elements->count() }}</p>
                            <p class="text-xs text-purple-700 mt-1">Total Elements</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Evidence Portfolio</h3>
                    <a href="{{ route('admin.apl02.evidence.create', ['unit_id' => $unit->id]) }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                        + Add Evidence
                    </a>
                </div>

                @if($unit->evidence->count() > 0)
                    <div class="space-y-3">
                        @foreach($unit->evidence as $evidence)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-sm font-semibold text-gray-900">{{ $evidence->evidence_number }}</span>
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">{{ $evidence->evidence_type_label }}</span>
                                        </div>
                                        <p class="text-sm text-gray-900 font-medium mb-1">{{ $evidence->title }}</p>
                                        <p class="text-xs text-gray-600">{{ $evidence->evidenceMaps->count() }} element mappings</p>
                                    </div>
                                    <a href="{{ route('admin.apl02.evidence.show', $evidence) }}" class="text-blue-600 hover:text-blue-800">
                                        <span class="material-symbols-outlined">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">folder_open</span>
                        <p class="text-sm">No evidence uploaded yet</p>
                    </div>
                @endif
            </div>

            <!-- Assessor Assignment -->
            @if(!$unit->assessor_id && $unit->is_submitted && $unit->event_id)
                @php
                    $autoAssessorId = $unit->getAssessorFromEventAssessors();
                    $autoAssessor = $autoAssessorId ? \App\Models\User::find($autoAssessorId) : null;
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assign Assessor</h3>

                    @if($autoAssessor)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-xs text-blue-700 mb-1">Assessor dari Event:</p>
                            <p class="font-semibold text-blue-900">{{ $autoAssessor->name }}</p>
                            <p class="text-xs text-blue-600">{{ $autoAssessor->email }}</p>
                        </div>
                        <form action="{{ route('admin.apl02.units.assign-assessor', $unit) }}" method="POST">
                            @csrf
                            <input type="hidden" name="assessor_id" value="{{ $autoAssessorId }}">
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">person_add</span>
                                Assign Assessor dari Event
                            </button>
                        </form>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-800">Tidak ada assessor yang tersedia dari event ini. Silakan assign assessor secara manual.</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Assessment Reviews -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Assessment Reviews</h3>
                </div>

                @if($unit->assessorReviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($unit->assessorReviews as $review)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-sm font-semibold text-gray-900">{{ $review->review_number }}</span>
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-800">{{ $review->review_type_label }}</span>
                                            @php
                                                $reviewStatusColors = [
                                                    'draft' => 'bg-gray-100 text-gray-800',
                                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'revision_required' => 'bg-orange-100 text-orange-800',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $reviewStatusColors[$review->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $review->status_label }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700 mb-1">Assessor: {{ $review->assessor->name }}</p>
                                        <p class="text-xs text-gray-600">Decision: {{ $review->decision_label }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($review->status === 'draft' || $review->status === 'in_progress')
                                            <a href="{{ route('admin.apl02.reviews.conduct', $review) }}" class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700">
                                                Conduct Review
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.apl02.reviews.show', $review) }}" class="text-blue-600 hover:text-blue-800">
                                            <span class="material-symbols-outlined">arrow_forward</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-symbols-outlined text-5xl mb-2 block text-gray-300">rate_review</span>
                        <p class="text-sm">No reviews yet</p>
                        @if(!$unit->assessor_id)
                            <p class="text-xs text-gray-400 mt-1">Assign assessor first to create review</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>

                <div class="space-y-3">
                    @if($unit->assigned_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">assignment</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Assigned to Assessor</p>
                                <p class="text-xs text-gray-500">{{ $unit->assigned_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($unit->started_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">play_arrow</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Assessment Started</p>
                                <p class="text-xs text-gray-500">{{ $unit->started_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($unit->submitted_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">send</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Submitted</p>
                                <p class="text-xs text-gray-500">{{ $unit->submitted_at->format('d M Y H:i') }}</p>
                                @if($unit->submittedBy)
                                    <p class="text-xs text-gray-500">by {{ $unit->submittedBy->name }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($unit->completed_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Completed</p>
                                <p class="text-xs text-gray-500">{{ $unit->completed_at->format('d M Y H:i') }}</p>
                                @if($unit->assessment_duration)
                                    <p class="text-xs text-gray-500">Duration: {{ $unit->assessment_duration }} minutes</p>
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
                    @if($unit->can_edit)
                        <a href="{{ route('admin.apl02.units.edit', $unit) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                            Edit Unit
                        </a>
                    @endif

                    @if(!$unit->is_locked && !$unit->is_submitted && $unit->status === 'in_progress')
                        <form action="{{ route('admin.apl02.units.submit', $unit) }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-center transition-all">
                                Submit Unit
                            </button>
                        </form>
                    @endif

                    @if($unit->is_locked)
                        <form action="{{ route('admin.apl02.units.unlock', $unit) }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full h-12 px-4 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 text-center transition-all">
                                Unlock Unit
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.apl02.units.lock', $unit) }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full h-12 px-4 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 text-center transition-all">
                                Lock Unit
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.apl02.units.calculate-completion', $unit) }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full h-12 px-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 text-center transition-all">
                            Recalculate Progress
                        </button>
                    </form>

                    <a href="{{ route('admin.apl02.units.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Created:</span><br>
                        {{ $unit->created_at->format('d M Y H:i') }}
                    </p>
                    @if($unit->updated_at != $unit->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Last Updated:</span><br>
                            {{ $unit->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Status Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Locked</span>
                        @if($unit->is_locked)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Submitted</span>
                        @if($unit->is_submitted)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Completed</span>
                        @if($unit->is_completed)
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Competent</span>
                        @if($unit->is_competent)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">No</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
