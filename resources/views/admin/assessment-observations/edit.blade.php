@extends('layouts.admin')

@section('title', 'Edit Observation')

@php
    $active = 'assessment-observations';
@endphp

@section('page_title', 'Edit Observation')
@section('page_description', $assessmentObservation->observation_number)

@section('content')
    <form action="{{ route('admin.assessment-observations.update', $assessmentObservation) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information (Read-only) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Observation Number</label>
                            <input type="text" value="{{ $assessmentObservation->observation_number }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment Unit</label>
                            <input type="text" value="{{ $assessmentObservation->assessmentUnit->unit_code }} - {{ $assessmentObservation->assessmentUnit->unit_title }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Assessee</label>
                            <input type="text" value="{{ $assessmentObservation->assessmentUnit->assessment->assessee->full_name }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Observation Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Observation Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Observer -->
                        <div class="md:col-span-2">
                            <label for="observer_id" class="block text-sm font-semibold text-gray-700 mb-2">Observer *</label>
                            <select id="observer_id" name="observer_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('observer_id') border-red-500 @enderror">
                                <option value="">Select Observer</option>
                                @foreach($observers as $observer)
                                    <option value="{{ $observer->id }}" {{ old('observer_id', $assessmentObservation->observer_id) == $observer->id ? 'selected' : '' }}>
                                        {{ $observer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('observer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Activity Observed -->
                        <div class="md:col-span-2">
                            <label for="activity_observed" class="block text-sm font-semibold text-gray-700 mb-2">Activity Observed *</label>
                            <input type="text" id="activity_observed" name="activity_observed" value="{{ old('activity_observed', $assessmentObservation->activity_observed) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('activity_observed') border-red-500 @enderror">
                            @error('activity_observed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Observation Date -->
                        <div>
                            <label for="observed_at" class="block text-sm font-semibold text-gray-700 mb-2">Observation Date *</label>
                            <input type="datetime-local" id="observed_at" name="observed_at" value="{{ old('observed_at', $assessmentObservation->observed_at?->format('Y-m-d\TH:i')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('observed_at') border-red-500 @enderror">
                            @error('observed_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">Duration (minutes)</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $assessmentObservation->duration_minutes) }}" min="1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('duration_minutes') border-red-500 @enderror">
                            @error('duration_minutes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="md:col-span-2">
                            <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $assessmentObservation->location) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Context -->
                        <div class="md:col-span-2">
                            <label for="context" class="block text-sm font-semibold text-gray-700 mb-2">Context</label>
                            <textarea id="context" name="context" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('context') border-red-500 @enderror">{{ old('context', $assessmentObservation->context) }}</textarea>
                            @error('context')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Observation Findings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Observation Findings</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- What Was Observed -->
                        <div>
                            <label for="what_was_observed" class="block text-sm font-semibold text-gray-700 mb-2">What Was Observed</label>
                            <textarea id="what_was_observed" name="what_was_observed" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('what_was_observed') border-red-500 @enderror">{{ old('what_was_observed', $assessmentObservation->what_was_observed) }}</textarea>
                            @error('what_was_observed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Performance Indicators -->
                        <div>
                            <label for="performance_indicators" class="block text-sm font-semibold text-gray-700 mb-2">Performance Indicators</label>
                            <textarea id="performance_indicators" name="performance_indicators" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('performance_indicators') border-red-500 @enderror">{{ old('performance_indicators', $assessmentObservation->performance_indicators) }}</textarea>
                            @error('performance_indicators')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Evidence Collected -->
                        <div>
                            <label for="evidence_collected" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Collected</label>
                            <textarea id="evidence_collected" name="evidence_collected" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('evidence_collected') border-red-500 @enderror">{{ old('evidence_collected', $assessmentObservation->evidence_collected) }}</textarea>
                            @error('evidence_collected')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Assessment Results -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Results</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Competency Demonstrated -->
                        <div>
                            <label for="competency_demonstrated" class="block text-sm font-semibold text-gray-700 mb-2">Competency Demonstrated</label>
                            <select id="competency_demonstrated" name="competency_demonstrated"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('competency_demonstrated') border-red-500 @enderror">
                                <option value="">Not Assessed</option>
                                <option value="yes" {{ old('competency_demonstrated', $assessmentObservation->competency_demonstrated) === 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="partial" {{ old('competency_demonstrated', $assessmentObservation->competency_demonstrated) === 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="no" {{ old('competency_demonstrated', $assessmentObservation->competency_demonstrated) === 'no' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('competency_demonstrated')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Score -->
                        <div>
                            <label for="score" class="block text-sm font-semibold text-gray-700 mb-2">Score (0-100)</label>
                            <input type="number" id="score" name="score" value="{{ old('score', $assessmentObservation->score) }}" min="0" max="100" step="0.1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('score') border-red-500 @enderror">
                            @error('score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Strengths -->
                        <div class="md:col-span-2">
                            <label for="strengths" class="block text-sm font-semibold text-gray-700 mb-2">Strengths</label>
                            <textarea id="strengths" name="strengths" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('strengths') border-red-500 @enderror">{{ old('strengths', $assessmentObservation->strengths) }}</textarea>
                            @error('strengths')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Areas for Improvement -->
                        <div class="md:col-span-2">
                            <label for="areas_for_improvement" class="block text-sm font-semibold text-gray-700 mb-2">Areas for Improvement</label>
                            <textarea id="areas_for_improvement" name="areas_for_improvement" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('areas_for_improvement') border-red-500 @enderror">{{ old('areas_for_improvement', $assessmentObservation->areas_for_improvement) }}</textarea>
                            @error('areas_for_improvement')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Observer Notes -->
                        <div class="md:col-span-2">
                            <label for="observer_notes" class="block text-sm font-semibold text-gray-700 mb-2">Observer Notes</label>
                            <textarea id="observer_notes" name="observer_notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('observer_notes') border-red-500 @enderror">{{ old('observer_notes', $assessmentObservation->observer_notes) }}</textarea>
                            @error('observer_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Follow-up -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Follow-up</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Requires Follow-up -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="requires_follow_up" value="0">
                                <input type="checkbox" id="requires_follow_up" name="requires_follow_up" value="1" {{ old('requires_follow_up', $assessmentObservation->requires_follow_up) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Requires Follow-up Action</span>
                            </label>
                        </div>

                        <!-- Follow-up Notes -->
                        <div>
                            <label for="follow_up_notes" class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Notes</label>
                            <textarea id="follow_up_notes" name="follow_up_notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('follow_up_notes') border-red-500 @enderror">{{ old('follow_up_notes', $assessmentObservation->follow_up_notes) }}</textarea>
                            @error('follow_up_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <!-- Form Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Save Changes
                        </button>

                        <a href="{{ route('admin.assessment-observations.show', $assessmentObservation) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">info</span>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-2">Tips</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Be specific in describing what was observed</li>
                                <li>• Note both strengths and areas for improvement</li>
                                <li>• Collect relevant evidence during observation</li>
                                <li>• Mark follow-up if additional assessment is needed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
