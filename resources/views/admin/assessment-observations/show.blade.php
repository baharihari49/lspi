@extends('layouts.admin')

@section('title', 'Observation Details')

@php
    $active = 'assessment-observations';
@endphp

@section('page_title', $assessmentObservation->observation_number)
@section('page_description', 'Observation assessment details')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Observation Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Observation Information</h3>
                    <a href="{{ route('admin.assessment-observations.edit', $assessmentObservation) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <span>Edit</span>
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-blue-900 text-4xl">visibility</span>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">{{ $assessmentObservation->observation_number }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $assessmentObservation->activity_observed }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">view_module</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessment Unit</p>
                                <a href="{{ route('admin.assessment-units.show', $assessmentObservation->assessmentUnit) }}" class="font-semibold text-blue-900 hover:underline">
                                    {{ $assessmentObservation->assessmentUnit->unit_code }}
                                </a>
                                <p class="text-xs text-gray-500">{{ Str::limit($assessmentObservation->assessmentUnit->unit_title, 40) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">person</span>
                            <div>
                                <p class="text-xs text-gray-600">Assessee</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentObservation->assessmentUnit->assessment->assessee->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $assessmentObservation->assessmentUnit->assessment->assessment_number }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">badge</span>
                            <div>
                                <p class="text-xs text-gray-600">Observer</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentObservation->observer->name ?? '-' }}</p>
                                @if($assessmentObservation->observer)
                                    <p class="text-xs text-gray-500">Assessor</p>
                                @else
                                    <p class="text-xs text-gray-500 italic">Not assigned</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">calendar_today</span>
                            <div>
                                <p class="text-xs text-gray-600">Observation Date</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentObservation->observed_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $assessmentObservation->observed_at->format('H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">schedule</span>
                            <div>
                                <p class="text-xs text-gray-600">Duration</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentObservation->duration_minutes }} minutes</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-gray-400">location_on</span>
                            <div>
                                <p class="text-xs text-gray-600">Location</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentObservation->location ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Observed Details -->
            @if($assessmentObservation->activity_description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Activity Description</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentObservation->activity_description }}</p>
                    </div>
                </div>
            @endif

            <!-- Observations & Findings -->
            @if($assessmentObservation->observations)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Observations & Findings</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentObservation->observations }}</p>
                    </div>
                </div>
            @endif

            <!-- Evidence & Supporting Materials -->
            @if($assessmentObservation->evidence)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Evidence & Supporting Materials</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentObservation->evidence }}</p>
                    </div>
                </div>
            @endif

            <!-- Observer Notes -->
            @if($assessmentObservation->notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Observer Notes</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $assessmentObservation->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Competency Assessment -->
            @if($assessmentObservation->competency_demonstrated !== null)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Competency Assessment</h3>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined {{ $assessmentObservation->competency_demonstrated ? 'text-green-600' : 'text-red-600' }} text-3xl">
                            {{ $assessmentObservation->competency_demonstrated ? 'check_circle' : 'cancel' }}
                        </span>
                        <div>
                            <p class="font-semibold text-gray-900">
                                {{ $assessmentObservation->competency_demonstrated ? 'Competency Demonstrated' : 'Competency Not Yet Demonstrated' }}
                            </p>
                            @if($assessmentObservation->competency_notes)
                                <p class="text-sm text-gray-600 mt-1">{{ $assessmentObservation->competency_notes }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Follow-up Actions -->
            @if($assessmentObservation->follow_up_required)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Follow-up Required</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        @if($assessmentObservation->follow_up_notes)
                            <p class="text-gray-900">{{ $assessmentObservation->follow_up_notes }}</p>
                        @else
                            <p class="text-gray-700">Follow-up action is required for this observation.</p>
                        @endif
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
                    <a href="{{ route('admin.assessment-observations.edit', $assessmentObservation) }}" class="block w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 text-center leading-[3rem] transition-all">
                        Edit Observation
                    </a>

                    <a href="{{ route('admin.assessment-units.show', $assessmentObservation->assessmentUnit) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        View Assessment Unit
                    </a>

                    <a href="{{ route('admin.assessment-observations.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                        Back to List
                    </a>

                    <form action="{{ route('admin.assessment-observations.destroy', $assessmentObservation) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this observation?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full h-12 px-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all">
                            Delete Observation
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
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentObservation->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $assessmentObservation->updated_at->format('d M Y') }}</span>
                    </div>
                    @if($assessmentObservation->created_by)
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-sm text-gray-600">Created By</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $assessmentObservation->creator->name ?? '-' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
