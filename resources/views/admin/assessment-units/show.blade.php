@extends('layouts.admin')

@section('title', 'Assessment Unit Details')

@php
    $active = 'assessment-units';
@endphp

@section('page_title', $assessmentUnit->unit_code)
@section('page_description', $assessmentUnit->unit_title)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Unit Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Unit Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Code</label>
                        <p class="text-gray-900">{{ $assessmentUnit->unit_code }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Title</label>
                        <p class="text-gray-900">{{ $assessmentUnit->unit_title }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                        <p class="text-gray-900">{{ $assessmentUnit->unit_description ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessment</label>
                        <a href="{{ route('admin.assessments.show', $assessmentUnit->assessment) }}" class="text-blue-900 hover:underline">
                            {{ $assessmentUnit->assessment->assessment_number }}
                        </a>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessee</label>
                        <p class="text-gray-900">{{ $assessmentUnit->assessment->assessee->full_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessor</label>
                        <p class="text-gray-900">{{ $assessmentUnit->assessor ? $assessmentUnit->assessor->name : 'Not assigned' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assessment Method</label>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ ucwords(str_replace('_', ' ', $assessmentUnit->assessment_method)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        @php
                            $statusColors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$assessmentUnit->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucwords(str_replace('_', ' ', $assessmentUnit->status)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Result</label>
                        @php
                            $resultColors = [
                                'pending' => 'bg-gray-100 text-gray-800',
                                'competent' => 'bg-green-100 text-green-800',
                                'not_yet_competent' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $resultColors[$assessmentUnit->result] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucwords(str_replace('_', ' ', $assessmentUnit->result)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Score</label>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $assessmentUnit->score ? number_format($assessmentUnit->score, 1) . '%' : '-' }}
                        </p>
                    </div>

                    @if($assessmentUnit->started_at)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Started At</label>
                            <p class="text-gray-900">{{ $assessmentUnit->started_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif

                    @if($assessmentUnit->completed_at)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Completed At</label>
                            <p class="text-gray-900">{{ $assessmentUnit->completed_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>

                @if($assessmentUnit->assessor_notes)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assessor Notes</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentUnit->assessor_notes }}</p>
                        </div>
                    </div>
                @endif

                @if($assessmentUnit->recommendations)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Recommendations</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentUnit->recommendations }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Assessment Criteria -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Assessment Criteria</h3>
                    @if($assessmentUnit->criteria && $assessmentUnit->criteria->count() > 0)
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold">{{ $assessmentUnit->criteria->where('result', 'competent')->count() }}</span> /
                            <span class="font-semibold">{{ $assessmentUnit->criteria->count() }}</span> Competent
                        </div>
                    @endif
                </div>

                @if($assessmentUnit->criteria && $assessmentUnit->criteria->count() > 0)
                    <div class="space-y-3">
                        @foreach($assessmentUnit->criteria as $criterion)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $criterion->is_critical ? 'bg-yellow-50 border-yellow-300' : '' }}">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-gray-900">{{ $criterion->element_code }}</span>
                                            @if($criterion->is_critical)
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded bg-yellow-200 text-yellow-800">
                                                    Critical
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-700">{{ $criterion->element_title }}</p>
                                        @if($criterion->notes)
                                            <p class="text-sm text-gray-600 mt-2">{{ $criterion->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $criterionResultColors = [
                                                'pending' => 'bg-gray-100 text-gray-800',
                                                'competent' => 'bg-green-100 text-green-800',
                                                'not_yet_competent' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $criterionResultColors[$criterion->result] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucwords(str_replace('_', ' ', $criterion->result)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No criteria available for this unit</p>
                @endif
            </div>

            <!-- Observations -->
            @if($assessmentUnit->observations && $assessmentUnit->observations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Observations</h3>
                    <div class="space-y-4">
                        @foreach($assessmentUnit->observations as $observation)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $observation->observation_date->format('d M Y H:i') }}</span>
                                    <span class="text-sm text-gray-600">{{ $observation->duration_minutes }} minutes</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $observation->notes }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Interviews -->
            @if($assessmentUnit->interviews && $assessmentUnit->interviews->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Interviews</h3>
                    <div class="space-y-4">
                        @foreach($assessmentUnit->interviews as $interview)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $interview->interview_date->format('d M Y H:i') }}</span>
                                    <span class="text-sm text-gray-600">{{ $interview->duration_minutes }} minutes</span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2"><strong>Questions:</strong> {{ $interview->questions }}</p>
                                <p class="text-sm text-gray-700"><strong>Responses:</strong> {{ $interview->responses }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Feedback -->
            @if($assessmentUnit->feedback && $assessmentUnit->feedback->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Feedback</h3>
                    <div class="space-y-4">
                        @foreach($assessmentUnit->feedback as $fb)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $fb->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $fb->feedback }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    @if($assessmentUnit->status === 'pending')
                        <form action="{{ route('admin.assessment-units.start', $assessmentUnit) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="w-full h-12 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">play_arrow</span>
                                Start Assessment
                            </button>
                        </form>
                    @endif

                    @if($assessmentUnit->status === 'in_progress')
                        <form action="{{ route('admin.assessment-units.complete', $assessmentUnit) }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">check_circle</span>
                                Complete Assessment
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.assessment-units.edit', $assessmentUnit) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        Edit Unit
                    </a>

                    <a href="{{ route('admin.assessments.show', $assessmentUnit->assessment) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        View Assessment
                    </a>

                    @if($assessmentUnit->status === 'pending')
                        <form action="{{ route('admin.assessment-units.destroy', $assessmentUnit) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this assessment unit?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all">
                                Delete Unit
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Criteria</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentUnit->criteria ? $assessmentUnit->criteria->count() : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Competent</span>
                        <span class="text-sm font-semibold text-green-600">{{ $assessmentUnit->criteria ? $assessmentUnit->criteria->where('result', 'competent')->count() : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Not Yet Competent</span>
                        <span class="text-sm font-semibold text-red-600">{{ $assessmentUnit->criteria ? $assessmentUnit->criteria->where('result', 'not_yet_competent')->count() : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Pending</span>
                        <span class="text-sm font-semibold text-gray-600">{{ $assessmentUnit->criteria ? $assessmentUnit->criteria->where('result', 'pending')->count() : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <span class="text-sm text-gray-600">Critical Criteria</span>
                        <span class="text-sm font-semibold text-yellow-600">{{ $assessmentUnit->criteria ? $assessmentUnit->criteria->where('is_critical', true)->count() : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Observations</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentUnit->observations ? $assessmentUnit->observations->count() : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Interviews</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentUnit->interviews ? $assessmentUnit->interviews->count() : 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
