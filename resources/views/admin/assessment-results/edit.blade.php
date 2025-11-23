@extends('layouts.admin')

@section('title', 'Edit Assessment Result')

@php
    $active = 'assessment-results';
@endphp

@section('page_title', 'Edit Assessment Result')
@section('page_description', 'Update assessment result information')

@section('content')
    <form action="{{ route('admin.assessment-results.update', $assessmentResult) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Assessment Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Information</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600">Result Number</p>
                                <p class="font-semibold text-gray-900">{{ $assessmentResult->result_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Assessment Number</p>
                                <p class="font-semibold text-gray-900">{{ $assessment->assessment_number }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600">Assessee</p>
                                <p class="font-semibold text-gray-900">{{ $assessment->assessee->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $assessment->assessee->assessee_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Scheme</p>
                                <p class="font-semibold text-gray-900">{{ $assessment->scheme->name }}</p>
                                <p class="text-xs text-gray-500">{{ $assessment->scheme->code }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assessment Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Statistics</h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['units_assessed'] }}</p>
                            <p class="text-xs text-blue-700 mt-1">Units Assessed</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-green-900">{{ $stats['units_competent'] }}</p>
                            <p class="text-xs text-green-700 mt-1">Units Competent</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['overall_score'], 1) }}%</p>
                            <p class="text-xs text-purple-700 mt-1">Overall Score</p>
                        </div>
                        <div class="bg-indigo-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-indigo-900">{{ $stats['criteria_met'] }}/{{ $stats['total_criteria'] }}</p>
                            <p class="text-xs text-indigo-700 mt-1">Criteria Met</p>
                        </div>
                    </div>

                    @if($stats['critical_criteria_total'] > 0)
                        <div class="mt-4 p-4 {{ $stats['all_critical_criteria_met'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} border rounded-lg">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined {{ $stats['all_critical_criteria_met'] ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $stats['all_critical_criteria_met'] ? 'check_circle' : 'error' }}
                                </span>
                                <div>
                                    <p class="font-semibold {{ $stats['all_critical_criteria_met'] ? 'text-green-900' : 'text-red-900' }}">
                                        Critical Criteria: {{ $stats['critical_criteria_met'] }}/{{ $stats['critical_criteria_total'] }}
                                    </p>
                                    <p class="text-xs {{ $stats['all_critical_criteria_met'] ? 'text-green-700' : 'text-red-700' }} mt-1">
                                        {{ $stats['all_critical_criteria_met'] ? 'All critical criteria have been met' : 'Not all critical criteria have been met' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Result Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Result Details</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Final Result -->
                        <div>
                            <label for="final_result" class="block text-sm font-semibold text-gray-700 mb-2">Final Result *</label>
                            <select id="final_result" name="final_result" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('final_result') border-red-500 @enderror">
                                <option value="">Select Result</option>
                                <option value="competent" {{ old('final_result', $assessmentResult->final_result) === 'competent' ? 'selected' : '' }}>Competent</option>
                                <option value="not_yet_competent" {{ old('final_result', $assessmentResult->final_result) === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                                <option value="requires_reassessment" {{ old('final_result', $assessmentResult->final_result) === 'requires_reassessment' ? 'selected' : '' }}>Requires Reassessment</option>
                            </select>
                            @error('final_result')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Executive Summary -->
                        <div>
                            <label for="executive_summary" class="block text-sm font-semibold text-gray-700 mb-2">Executive Summary *</label>
                            <textarea id="executive_summary" name="executive_summary" rows="4" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('executive_summary') border-red-500 @enderror">{{ old('executive_summary', $assessmentResult->executive_summary) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Provide a comprehensive overview of the assessment results</p>
                            @error('executive_summary')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Performance Analysis -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Performance Analysis</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Key Strengths -->
                        <div>
                            <label for="key_strengths" class="block text-sm font-semibold text-gray-700 mb-2">Key Strengths</label>
                            <textarea id="key_strengths" name="key_strengths[]" rows="3" placeholder="Enter key strengths (one per line)"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('key_strengths') border-red-500 @enderror">{{ old('key_strengths.0', $assessmentResult->key_strengths ? implode("\n", $assessmentResult->key_strengths) : '') }}</textarea>
                            @error('key_strengths')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Development Areas -->
                        <div>
                            <label for="development_areas" class="block text-sm font-semibold text-gray-700 mb-2">Development Areas</label>
                            <textarea id="development_areas" name="development_areas[]" rows="3" placeholder="Enter areas for development (one per line)"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('development_areas') border-red-500 @enderror">{{ old('development_areas.0', $assessmentResult->development_areas ? implode("\n", $assessmentResult->development_areas) : '') }}</textarea>
                            @error('development_areas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Overall Performance Notes -->
                        <div>
                            <label for="overall_performance_notes" class="block text-sm font-semibold text-gray-700 mb-2">Overall Performance Notes</label>
                            <textarea id="overall_performance_notes" name="overall_performance_notes" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('overall_performance_notes') border-red-500 @enderror">{{ old('overall_performance_notes', $assessmentResult->overall_performance_notes) }}</textarea>
                            @error('overall_performance_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Recommendations & Next Steps -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Recommendations & Next Steps</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Recommendations -->
                        <div>
                            <label for="recommendations" class="block text-sm font-semibold text-gray-700 mb-2">Recommendations</label>
                            <textarea id="recommendations" name="recommendations[]" rows="3" placeholder="Enter recommendations (one per line)"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('recommendations') border-red-500 @enderror">{{ old('recommendations.0', $assessmentResult->recommendations ? implode("\n", $assessmentResult->recommendations) : '') }}</textarea>
                            @error('recommendations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Next Steps -->
                        <div>
                            <label for="next_steps" class="block text-sm font-semibold text-gray-700 mb-2">Next Steps</label>
                            <textarea id="next_steps" name="next_steps" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('next_steps') border-red-500 @enderror">{{ old('next_steps', $assessmentResult->next_steps) }}</textarea>
                            @error('next_steps')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reassessment Plan (if applicable) -->
                        <div>
                            <label for="reassessment_plan" class="block text-sm font-semibold text-gray-700 mb-2">Reassessment Plan (if applicable)</label>
                            <textarea id="reassessment_plan" name="reassessment_plan" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('reassessment_plan') border-red-500 @enderror">{{ old('reassessment_plan', $assessmentResult->reassessment_plan) }}</textarea>
                            @error('reassessment_plan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Certification Details (for Competent results) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Certification Details</h3>
                    <p class="text-sm text-gray-600 mb-4">These fields are required if the final result is "Competent"</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Certification Date -->
                        <div>
                            <label for="certification_date" class="block text-sm font-semibold text-gray-700 mb-2">Certification Date</label>
                            <input type="date" id="certification_date" name="certification_date" value="{{ old('certification_date', $assessmentResult->certification_date?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certification_date') border-red-500 @enderror">
                            @error('certification_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certification Expiry Date -->
                        <div>
                            <label for="certification_expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Certification Expiry Date</label>
                            <input type="date" id="certification_expiry_date" name="certification_expiry_date" value="{{ old('certification_expiry_date', $assessmentResult->certification_expiry_date?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certification_expiry_date') border-red-500 @enderror">
                            @error('certification_expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certification Level -->
                        <div class="md:col-span-2">
                            <label for="certification_level" class="block text-sm font-semibold text-gray-700 mb-2">Certification Level</label>
                            <input type="text" id="certification_level" name="certification_level" value="{{ old('certification_level', $assessmentResult->certification_level) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certification_level') border-red-500 @enderror">
                            @error('certification_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all">
                            Update Result
                        </button>

                        <a href="{{ route('admin.assessment-results.show', $assessmentResult) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Result Number:</span><br>
                            {{ $assessmentResult->result_number }}
                        </p>
                        @if($assessmentResult->certificate_number)
                            <p class="text-sm text-gray-600 mt-2">
                                <span class="font-semibold">Certificate:</span><br>
                                {{ $assessmentResult->certificate_number }}
                            </p>
                        @endif
                        <p class="text-sm text-gray-600 mt-2">
                            <span class="font-semibold">Status:</span><br>
                            {{ ucwords(str_replace('_', ' ', $assessmentResult->approval_status)) }}
                        </p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Stats</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Units Competent</span>
                            <span class="font-bold text-gray-900">{{ $stats['units_competent'] }}/{{ $stats['units_assessed'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Criteria Pass Rate</span>
                            <span class="font-bold text-gray-900">{{ number_format($stats['criteria_percentage'], 1) }}%</span>
                        </div>
                        @if($stats['critical_criteria_total'] > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Critical Criteria</span>
                                <span class="font-bold {{ $stats['all_critical_criteria_met'] ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $stats['critical_criteria_met'] }}/{{ $stats['critical_criteria_total'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
