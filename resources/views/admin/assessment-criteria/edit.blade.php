@extends('layouts.admin')

@section('title', 'Edit Assessment Criterion')

@php
    $active = 'assessment-criteria';
@endphp

@section('page_title', 'Edit Assessment Criterion')
@section('page_description', $assessmentCriterion->element_code . ' - ' . $assessmentCriterion->element_title)

@section('content')
    <form action="{{ route('admin.assessment-criteria.update', $assessmentCriterion) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Criterion Information (Read-only) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Criterion Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Element Code</label>
                            <input type="text" value="{{ $assessmentCriterion->element_code }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Element Title</label>
                            <textarea disabled rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">{{ $assessmentCriterion->element_title }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment Unit</label>
                            <input type="text" value="{{ $assessmentCriterion->assessmentUnit->unit_code }} - {{ $assessmentCriterion->assessmentUnit->unit_title }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Assessment Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Result -->
                        <div>
                            <label for="result" class="block text-sm font-semibold text-gray-700 mb-2">Result *</label>
                            <select id="result" name="result" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('result') border-red-500 @enderror">
                                <option value="pending" {{ old('result', $assessmentCriterion->result) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="competent" {{ old('result', $assessmentCriterion->result) === 'competent' ? 'selected' : '' }}>Competent</option>
                                <option value="not_yet_competent" {{ old('result', $assessmentCriterion->result) === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                            </select>
                            @error('result')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assessment Method -->
                        <div>
                            <label for="assessment_method" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Method *</label>
                            <select id="assessment_method" name="assessment_method" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_method') border-red-500 @enderror">
                                <option value="">Select Method</option>
                                <option value="portfolio" {{ old('assessment_method', $assessmentCriterion->assessment_method) === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                <option value="observation" {{ old('assessment_method', $assessmentCriterion->assessment_method) === 'observation' ? 'selected' : '' }}>Observation</option>
                                <option value="interview" {{ old('assessment_method', $assessmentCriterion->assessment_method) === 'interview' ? 'selected' : '' }}>Interview</option>
                                <option value="demonstration" {{ old('assessment_method', $assessmentCriterion->assessment_method) === 'demonstration' ? 'selected' : '' }}>Demonstration</option>
                                <option value="written_test" {{ old('assessment_method', $assessmentCriterion->assessment_method) === 'written_test' ? 'selected' : '' }}>Written Test</option>
                                <option value="mixed" {{ old('assessment_method', $assessmentCriterion->assessment_method) === 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                            @error('assessment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Critical Flag -->
                        <div class="md:col-span-2">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="is_critical" name="is_critical" value="1"
                                    {{ old('is_critical', $assessmentCriterion->is_critical) ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_critical" class="text-sm font-semibold text-gray-700">Mark as Critical Criterion</label>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">Critical criteria must be met for overall competency</p>
                            @error('is_critical')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Evidence and Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Evidence and Notes</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Evidence Collected -->
                        <div>
                            <label for="evidence_collected" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Collected</label>
                            <textarea id="evidence_collected" name="evidence_collected" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('evidence_collected') border-red-500 @enderror">{{ old('evidence_collected', $assessmentCriterion->evidence_collected) }}</textarea>
                            @error('evidence_collected')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-600">Describe the evidence collected for this criterion</p>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror">{{ old('notes', $assessmentCriterion->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assessor Feedback -->
                        <div>
                            <label for="assessor_feedback" class="block text-sm font-semibold text-gray-700 mb-2">Assessor Feedback</label>
                            <textarea id="assessor_feedback" name="assessor_feedback" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_feedback') border-red-500 @enderror">{{ old('assessor_feedback', $assessmentCriterion->assessor_feedback) }}</textarea>
                            @error('assessor_feedback')
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
                            Update Criterion
                        </button>

                        <a href="{{ route('admin.assessment-criteria.show', $assessmentCriterion) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Note:</span> Fields marked with * are required.
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            Assessment date and assessor will be automatically recorded.
                        </p>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Current Status</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Current Result</span>
                            @php
                                $resultColors = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'competent' => 'bg-green-100 text-green-800',
                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $resultColors[$assessmentCriterion->result] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucwords(str_replace('_', ' ', $assessmentCriterion->result)) }}
                            </span>
                        </div>

                        @if($assessmentCriterion->is_critical)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Critical</span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">
                                    Yes
                                </span>
                            </div>
                        @endif

                        @if($assessmentCriterion->assessed_at)
                            <div class="pt-3 border-t border-gray-200">
                                <div class="text-sm text-gray-600 mb-1">Last Assessed</div>
                                <div class="text-sm font-semibold text-gray-900">{{ $assessmentCriterion->assessed_at->format('d M Y H:i') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Context -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Context</h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <div class="text-gray-600 mb-1">Assessee</div>
                            <div class="font-semibold text-gray-900">{{ $assessmentCriterion->assessmentUnit->assessment->assessee->full_name }}</div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <div class="text-gray-600 mb-1">Unit Progress</div>
                            <div class="font-semibold text-gray-900">
                                {{ $assessmentCriterion->assessmentUnit->criteria->where('result', 'competent')->count() }} /
                                {{ $assessmentCriterion->assessmentUnit->criteria->count() }} Competent
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <a href="{{ route('admin.assessment-units.show', $assessmentCriterion->assessmentUnit) }}" class="text-blue-900 hover:underline text-sm font-semibold">
                                View Full Unit →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
