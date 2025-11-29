@extends('layouts.admin')

@section('title', 'Assessment Details')

@php
    $active = 'assessments';
@endphp

@section('page_title', $assessment->assessment_number)
@section('page_description', 'Assessment details and progress tracking')

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
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Assessment Information</h3>
                    @if($assessment->canBeModified())
                        <a href="{{ route('admin.assessments.edit', $assessment) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit Assessment</span>
                        </a>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-blue-900 text-4xl">assignment</span>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $assessment->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $assessment->assessment_number }}</p>
                            @if($assessment->description)
                                <p class="text-sm text-gray-700 mt-2">{{ $assessment->description }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'scheduled' => 'bg-blue-100 text-blue-700',
                                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                                        'completed' => 'bg-purple-100 text-purple-700',
                                        'under_review' => 'bg-orange-100 text-orange-700',
                                        'verified' => 'bg-indigo-100 text-indigo-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'cancelled' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $resultColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'competent' => 'bg-green-100 text-green-700',
                                        'not_yet_competent' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $statusColors[$assessment->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ ucwords(str_replace('_', ' ', $assessment->status)) }}
                                </span>
                                <span class="px-3 py-1 {{ $resultColors[$assessment->overall_result] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-semibold">
                                    {{ ucwords(str_replace('_', ' ', $assessment->overall_result)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">person</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessee</p>
                                <p class="font-semibold text-gray-900">{{ $assessment->assessee->full_name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $assessment->assessee->assessee_number ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">workspace_premium</span>
                            <div>
                                <p class="text-xs text-gray-600">Scheme</p>
                                <p class="font-semibold text-gray-900">{{ $assessment->scheme->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $assessment->scheme->code ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">badge</span>
                            <div>
                                <p class="text-xs text-gray-600">Lead Assessor</p>
                                <p class="font-semibold text-gray-900">{{ $assessment->leadAssessor->name ?? '-' }}</p>
                            </div>
                        </div>

                        @if($assessment->event)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">event</span>
                                <div>
                                    <p class="text-xs text-gray-600">Event</p>
                                    <p class="font-semibold text-gray-900">{{ $assessment->event->name }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessment->eventSession)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">schedule</span>
                                <div>
                                    <p class="text-xs text-gray-600">Sesi</p>
                                    <p class="font-semibold text-gray-900">{{ $assessment->eventSession->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $assessment->eventSession->session_date?->format('d M Y') }}
                                        {{ $assessment->eventSession->start_time?->format('H:i') }} - {{ $assessment->eventSession->end_time?->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">calendar_today</span>
                            <div>
                                <p class="text-xs text-gray-600">Scheduled Date</p>
                                <p class="font-semibold text-gray-900">{{ $assessment->scheduled_date ? $assessment->scheduled_date->format('d M Y') : '-' }}</p>
                                @if($assessment->scheduled_time)
                                    <p class="text-xs text-gray-500">{{ $assessment->scheduled_time->format('H:i') }}</p>
                                @endif
                            </div>
                        </div>

                        @php
                            // Fallback: use TUK from event if assessment TUK is not set
                            $tuk = $assessment->tuk ?? $assessment->event?->tuks?->first()?->tuk;
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">location_on</span>
                            <div>
                                <p class="text-xs text-gray-600">Venue / TUK</p>
                                <p class="font-semibold text-gray-900">{{ $tuk?->name ?? $assessment->venue ?? 'No venue' }}</p>
                                @if($tuk?->address)
                                    <p class="text-xs text-gray-500">{{ $tuk->address }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">category</span>
                            <div>
                                <p class="text-xs text-gray-600">Type & Method</p>
                                <p class="font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $assessment->assessment_type)) }}</p>
                                <p class="text-xs text-gray-500">{{ ucwords($assessment->assessment_method) }}</p>
                            </div>
                        </div>

                        @if($assessment->started_at)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">play_circle</span>
                                <div>
                                    <p class="text-xs text-gray-600">Started At</p>
                                    <p class="font-semibold text-gray-900">{{ $assessment->started_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($assessment->completed_at)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">check_circle</span>
                                <div>
                                    <p class="text-xs text-gray-600">Completed At</p>
                                    <p class="font-semibold text-gray-900">{{ $assessment->completed_at->format('d M Y H:i') }}</p>
                                    @if($assessment->duration_minutes)
                                        <p class="text-xs text-gray-500">Duration: {{ $assessment->duration_minutes }} minutes</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($assessment->notes)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Notes</p>
                            <p class="text-sm text-gray-700">{{ $assessment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assessment Units -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Assessment Units ({{ $assessment->assessmentUnits->count() }})</h3>
                    @if($assessment->assessmentUnits->count() === 0 && $assessment->status === 'draft')
                        <form action="{{ route('admin.assessments.generate-units', $assessment) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                                <span class="material-symbols-outlined text-sm">auto_awesome</span>
                                <span>Generate from Scheme</span>
                            </button>
                        </form>
                    @endif
                </div>

                @if($assessment->assessmentUnits->count() > 0)
                    <div class="space-y-4">
                        @foreach($assessment->assessmentUnits as $unit)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $unit->unit_code }}</h4>
                                        <p class="text-sm text-gray-600">{{ $unit->unit_title }}</p>
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            @php
                                                $unitResultColors = [
                                                    'pending' => 'bg-gray-100 text-gray-700',
                                                    'competent' => 'bg-green-100 text-green-700',
                                                    'not_yet_competent' => 'bg-red-100 text-red-700',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 {{ $unitResultColors[$unit->result] ?? 'bg-gray-100 text-gray-700' }} rounded text-xs font-semibold">
                                                {{ ucwords(str_replace('_', ' ', $unit->result)) }}
                                            </span>
                                            @if($unit->score)
                                                <span class="text-xs text-gray-500">Score: {{ number_format($unit->score, 1) }}%</span>
                                            @endif
                                            <span class="text-xs text-gray-500">{{ $unit->criteria->count() }} criteria</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No assessment units yet. Click "Generate from Scheme" to create units.</p>
                @endif
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Documents ({{ $assessment->documents->count() }})</h3>
                </div>

                @if($assessment->documents->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($assessment->documents as $document)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-600">description</span>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $document->document_name }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $document->file_size_formatted ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No documents uploaded yet.</p>
                @endif
            </div>

            <!-- Results -->
            @if($assessment->results->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Results</h3>

                    @foreach($assessment->results as $result)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $result->result_number }}</p>
                                    @if($result->certificate_number)
                                        <p class="text-sm text-gray-600">{{ $result->certificate_number }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('admin.assessment-results.show', $result) }}" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-sm font-semibold transition">
                                    View Result
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    @if($assessment->canBeModified())
                        <a href="{{ route('admin.assessments.edit', $assessment) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                            Edit Assessment
                        </a>
                    @endif

                    @if($assessment->isCompleted() && !$assessment->results()->exists())
                        <a href="{{ route('admin.assessment-results.create', ['assessment_id' => $assessment->id]) }}" class="block w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 text-center leading-[3rem] transition-all">
                            Create Result
                        </a>
                    @endif

                    <!-- Status Update -->
                    <form action="{{ route('admin.assessments.update-status', $assessment) }}" method="POST" class="space-y-3">
                        @csrf
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                            <option value="draft" {{ $assessment->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending_confirmation" {{ $assessment->status === 'pending_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="scheduled" {{ $assessment->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="in_progress" {{ $assessment->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $assessment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="under_review" {{ $assessment->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="verified" {{ $assessment->status === 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="approved" {{ $assessment->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="cancelled" {{ $assessment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="w-full h-10 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 text-sm transition-all">
                            Update Status
                        </button>
                    </form>

                    <a href="{{ route('admin.assessments.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>

                    @if($assessment->status === 'draft')
                        <form action="{{ route('admin.assessments.destroy', $assessment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessment?')" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all">
                                Delete Assessment
                            </button>
                        </form>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-semibold">Created:</span><br>
                        {{ $assessment->created_at->format('d M Y H:i') }}
                    </p>
                    @if($assessment->updated_at != $assessment->created_at)
                        <p class="text-xs text-gray-600 mt-2">
                            <span class="font-semibold">Last Updated:</span><br>
                            {{ $assessment->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Units</span>
                        <span class="font-bold text-gray-900">{{ $assessment->assessmentUnits->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Documents</span>
                        <span class="font-bold text-gray-900">{{ $assessment->documents->count() }}</span>
                    </div>
                    @if($assessment->overall_score)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Overall Score</span>
                            <span class="font-bold text-gray-900">{{ number_format($assessment->overall_score, 1) }}%</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
