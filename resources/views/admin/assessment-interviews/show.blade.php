@extends('layouts.admin')

@section('title', 'Interview Details')

@php
    $active = 'assessment-interviews';
@endphp

@section('page_title', $assessmentInterview->interview_number)
@section('page_description', 'Interview assessment details')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Interview Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Interview Information</h3>
                    <a href="{{ route('admin.assessment-interviews.edit', $assessmentInterview) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit</span>
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-blue-900 text-4xl">question_answer</span>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $assessmentInterview->interview_number }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $assessmentInterview->session_title }}</p>
                            @if($assessmentInterview->purpose)
                                <p class="text-sm text-gray-700 mt-2">{{ $assessmentInterview->purpose }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">view_module</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessment Unit</p>
                                <a href="{{ route('admin.assessment-units.show', $assessmentInterview->assessmentUnit) }}" class="font-semibold text-blue-900 hover:underline">
                                    {{ $assessmentInterview->assessmentUnit->unit_code }}
                                </a>
                                <p class="text-xs text-gray-500">{{ Str::limit($assessmentInterview->assessmentUnit->unit_title, 40) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">person</span>
                            <div>
                                <p class="text-xs text-gray-600">Interviewee (Assessee)</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentInterview->interviewee->name }}</p>
                                <p class="text-xs text-gray-500">{{ $assessmentInterview->assessmentUnit->assessment->assessment_number }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">badge</span>
                            <div>
                                <p class="text-xs text-gray-600">Interviewer</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentInterview->interviewer->name ?? '-' }}</p>
                                @if($assessmentInterview->interviewer)
                                    <p class="text-xs text-gray-500">Assessor</p>
                                @else
                                    <p class="text-xs text-gray-500 italic">Not assigned</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">calendar_today</span>
                            <div>
                                <p class="text-xs text-gray-600">Interview Date</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentInterview->conducted_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $assessmentInterview->conducted_at->format('H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">schedule</span>
                            <div>
                                <p class="text-xs text-gray-600">Duration</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentInterview->duration_minutes }} minutes</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">location_on</span>
                            <div>
                                <p class="text-xs text-gray-600">Location</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentInterview->location ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">video_call</span>
                            <div>
                                <p class="text-xs text-gray-600">Interview Method</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    {{ ucwords(str_replace('_', ' ', $assessmentInterview->interview_method)) }}
                                </span>
                            </div>
                        </div>

                        @if($assessmentInterview->overall_assessment)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">assessment</span>
                                <div>
                                    <p class="text-xs text-gray-600">Overall Assessment</p>
                                    @php
                                        $assessmentColors = [
                                            'fully_satisfactory' => 'bg-green-100 text-green-700',
                                            'satisfactory' => 'bg-blue-100 text-blue-700',
                                            'needs_improvement' => 'bg-yellow-100 text-yellow-700',
                                            'unsatisfactory' => 'bg-red-100 text-red-700',
                                        ];
                                        $assessmentColor = $assessmentColors[$assessmentInterview->overall_assessment] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $assessmentColor }}">
                                        {{ ucwords(str_replace('_', ' ', $assessmentInterview->overall_assessment)) }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($assessmentInterview->score)
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">grade</span>
                                <div>
                                    <p class="text-xs text-gray-600">Score</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ number_format($assessmentInterview->score, 1) }}%</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Questions & Answers -->
            @if($assessmentInterview->questions)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Questions & Answers</h3>
                    <div class="space-y-4">
                        @foreach($assessmentInterview->questions as $index => $qa)
                            <div class="border border-gray-200 rounded-lg p-4 {{ isset($qa['satisfactory']) && $qa['satisfactory'] ? 'bg-green-50 border-green-200' : '' }}">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-sm font-semibold text-blue-900">Q{{ $index + 1 }}</span>
                                            @if(isset($qa['competency_area']))
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded bg-purple-100 text-purple-700">
                                                    {{ $qa['competency_area'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900 mb-2">{{ $qa['question'] ?? '-' }}</p>
                                        <p class="text-sm text-gray-700 bg-gray-50 rounded p-3 mb-2">{{ $qa['answer'] ?? '-' }}</p>
                                        @if(isset($qa['notes']))
                                            <p class="text-xs text-gray-600 mt-2"><strong>Notes:</strong> {{ $qa['notes'] }}</p>
                                        @endif
                                    </div>
                                    @if(isset($qa['satisfactory']))
                                        <div>
                                            <span class="material-symbols-outlined {{ $qa['satisfactory'] ? 'text-green-600' : 'text-red-600' }} text-2xl">
                                                {{ $qa['satisfactory'] ? 'check_circle' : 'cancel' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Key Findings -->
            @if($assessmentInterview->key_findings)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Key Findings</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentInterview->key_findings }}</p>
                    </div>
                </div>
            @endif

            <!-- Competencies & Gaps -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($assessmentInterview->competencies_demonstrated)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Competencies Demonstrated</h3>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentInterview->competencies_demonstrated }}</p>
                        </div>
                    </div>
                @endif

                @if($assessmentInterview->gaps_identified)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Gaps Identified</h3>
                        <div class="bg-red-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentInterview->gaps_identified }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Observations -->
            @if($assessmentInterview->behavioral_observations || $assessmentInterview->technical_observations)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Observations</h3>

                    @if($assessmentInterview->behavioral_observations)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Behavioral Observations</h4>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentInterview->behavioral_observations }}</p>
                            </div>
                        </div>
                    @endif

                    @if($assessmentInterview->technical_observations)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Technical Observations</h4>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentInterview->technical_observations }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Interviewer Notes -->
            @if($assessmentInterview->interviewer_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Interviewer Notes</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentInterview->interviewer_notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Follow-up -->
            @if($assessmentInterview->requires_follow_up)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Follow-up Required</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        @if($assessmentInterview->follow_up_items)
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentInterview->follow_up_items }}</p>
                        @else
                            <p class="text-gray-700">Follow-up action is required for this interview.</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Transcript -->
            @if($assessmentInterview->transcript)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Interview Transcript</h3>
                    <div class="bg-gray-50 rounded-lg p-4 max-h-96 overflow-y-auto">
                        <p class="text-gray-900 whitespace-pre-wrap text-sm">{{ $assessmentInterview->transcript }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Metadata -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.assessment-interviews.edit', $assessmentInterview) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        Edit Interview
                    </a>

                    <a href="{{ route('admin.assessment-units.show', $assessmentInterview->assessmentUnit) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        View Assessment Unit
                    </a>

                    @if($assessmentInterview->recording_file_path)
                        <a href="{{ Storage::url($assessmentInterview->recording_file_path) }}" target="_blank" class="block w-full h-12 px-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 text-center leading-[3rem] transition-all">
                            View Recording
                        </a>
                    @endif

                    <a href="{{ route('admin.assessment-interviews.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>

                    <form action="{{ route('admin.assessment-interviews.destroy', $assessmentInterview) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this interview?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all">
                            Delete Interview
                        </button>
                    </form>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Metadata</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Created</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentInterview->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentInterview->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
