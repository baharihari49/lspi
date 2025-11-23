@extends('layouts.admin')

@section('title', 'Edit Assessment Unit')

@php
    $active = 'assessment-units';
@endphp

@section('page_title', 'Edit Assessment Unit')
@section('page_description', $assessmentUnit->unit_code . ' - ' . $assessmentUnit->unit_title)

@section('content')
    <form action="{{ route('admin.assessment-units.update', $assessmentUnit) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Unit Information (Read-only) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Unit Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Code</label>
                            <input type="text" value="{{ $assessmentUnit->unit_code }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Title</label>
                            <input type="text" value="{{ $assessmentUnit->unit_title }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment</label>
                            <input type="text" value="{{ $assessmentUnit->assessment->assessment_number }} - {{ $assessmentUnit->assessment->assessee->full_name }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Assessment Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Assessor -->
                        <div>
                            <label for="assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessor</label>
                            <select id="assessor_id" name="assessor_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_id') border-red-500 @enderror">
                                <option value="">Select Assessor</option>
                                @foreach($assessors as $assessor)
                                    <option value="{{ $assessor->id }}" {{ old('assessor_id', $assessmentUnit->assessor_id) == $assessor->id ? 'selected' : '' }}>
                                        {{ $assessor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assessment Method -->
                        <div>
                            <label for="assessment_method" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Method *</label>
                            <select id="assessment_method" name="assessment_method" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_method') border-red-500 @enderror">
                                <option value="">Select Method</option>
                                <option value="portfolio" {{ old('assessment_method', $assessmentUnit->assessment_method) === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                <option value="observation" {{ old('assessment_method', $assessmentUnit->assessment_method) === 'observation' ? 'selected' : '' }}>Observation</option>
                                <option value="interview" {{ old('assessment_method', $assessmentUnit->assessment_method) === 'interview' ? 'selected' : '' }}>Interview</option>
                                <option value="demonstration" {{ old('assessment_method', $assessmentUnit->assessment_method) === 'demonstration' ? 'selected' : '' }}>Demonstration</option>
                                <option value="written_test" {{ old('assessment_method', $assessmentUnit->assessment_method) === 'written_test' ? 'selected' : '' }}>Written Test</option>
                                <option value="mixed" {{ old('assessment_method', $assessmentUnit->assessment_method) === 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                            @error('assessment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status') border-red-500 @enderror">
                                <option value="pending" {{ old('status', $assessmentUnit->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $assessmentUnit->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $assessmentUnit->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Result -->
                        <div>
                            <label for="result" class="block text-sm font-semibold text-gray-700 mb-2">Result *</label>
                            <select id="result" name="result" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('result') border-red-500 @enderror">
                                <option value="pending" {{ old('result', $assessmentUnit->result) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="competent" {{ old('result', $assessmentUnit->result) === 'competent' ? 'selected' : '' }}>Competent</option>
                                <option value="not_yet_competent" {{ old('result', $assessmentUnit->result) === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                            </select>
                            @error('result')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Score -->
                        <div class="md:col-span-2">
                            <label for="score" class="block text-sm font-semibold text-gray-700 mb-2">Score (0-100)</label>
                            <input type="number" id="score" name="score" value="{{ old('score', $assessmentUnit->score) }}" min="0" max="100" step="0.1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('score') border-red-500 @enderror">
                            @error('score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-600">Leave blank to auto-calculate from criteria results</p>
                        </div>
                    </div>
                </div>

                <!-- Notes and Recommendations -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Notes and Recommendations</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Assessor Notes -->
                        <div>
                            <label for="assessor_notes" class="block text-sm font-semibold text-gray-700 mb-2">Assessor Notes</label>
                            <textarea id="assessor_notes" name="assessor_notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_notes') border-red-500 @enderror">{{ old('assessor_notes', $assessmentUnit->assessor_notes) }}</textarea>
                            @error('assessor_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recommendations -->
                        <div>
                            <label for="recommendations" class="block text-sm font-semibold text-gray-700 mb-2">Recommendations</label>
                            <textarea id="recommendations" name="recommendations" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('recommendations') border-red-500 @enderror">{{ old('recommendations', $assessmentUnit->recommendations) }}</textarea>
                            @error('recommendations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Criteria Summary (Read-only) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Criteria Summary</h3>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ $assessmentUnit->criteria->count() }}</div>
                            <div class="text-sm text-gray-600 mt-1">Total Criteria</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $assessmentUnit->criteria->where('result', 'competent')->count() }}</div>
                            <div class="text-sm text-gray-600 mt-1">Competent</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-600">{{ $assessmentUnit->criteria->where('is_critical', true)->count() }}</div>
                            <div class="text-sm text-gray-600 mt-1">Critical</div>
                        </div>
                    </div>

                    @if($assessmentUnit->criteria->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-700">Completion Progress</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $assessmentUnit->criteria->where('result', 'competent')->count() }} / {{ $assessmentUnit->criteria->count() }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-900 h-2 rounded-full" style="width: {{ $assessmentUnit->criteria->count() > 0 ? ($assessmentUnit->criteria->where('result', 'competent')->count() / $assessmentUnit->criteria->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-200 transition-all">
                            Update Assessment Unit
                        </button>

                        <a href="{{ route('admin.assessment-units.show', $assessmentUnit) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Note:</span> Fields marked with * are required.
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            Score will be auto-calculated based on criteria if left blank.
                        </p>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Current Status</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$assessmentUnit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucwords(str_replace('_', ' ', $assessmentUnit->status)) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Result</span>
                            @php
                                $resultColors = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'competent' => 'bg-green-100 text-green-800',
                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $resultColors[$assessmentUnit->result] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucwords(str_replace('_', ' ', $assessmentUnit->result)) }}
                            </span>
                        </div>

                        @if($assessmentUnit->score)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Current Score</span>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($assessmentUnit->score, 1) }}%</span>
                            </div>
                        @endif

                        @if($assessmentUnit->started_at)
                            <div class="pt-3 border-t border-gray-200">
                                <div class="text-sm text-gray-600 mb-1">Started At</div>
                                <div class="text-sm font-semibold text-gray-900">{{ $assessmentUnit->started_at->format('d M Y H:i') }}</div>
                            </div>
                        @endif

                        @if($assessmentUnit->completed_at)
                            <div class="pt-3 border-t border-gray-200">
                                <div class="text-sm text-gray-600 mb-1">Completed At</div>
                                <div class="text-sm font-semibold text-gray-900">{{ $assessmentUnit->completed_at->format('d M Y H:i') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
