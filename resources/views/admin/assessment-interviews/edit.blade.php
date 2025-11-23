@extends('layouts.admin')

@section('title', 'Edit Interview')

@php
    $active = 'assessment-interviews';
@endphp

@section('page_title', 'Edit Interview')
@section('page_description', $assessmentInterview->interview_number)

@section('content')
    <form action="{{ route('admin.assessment-interviews.update', $assessmentInterview) }}" method="POST" class="w-full">
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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Interview Number</label>
                            <input type="text" value="{{ $assessmentInterview->interview_number }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment Unit</label>
                            <input type="text" value="{{ $assessmentInterview->assessmentUnit->unit_code }} - {{ $assessmentInterview->assessmentUnit->unit_title }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Interviewee (Assessee)</label>
                            <input type="text" value="{{ $assessmentInterview->interviewee->name }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Interview Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Interview Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Interviewer -->
                        <div class="md:col-span-2">
                            <label for="interviewer_id" class="block text-sm font-semibold text-gray-700 mb-2">Interviewer *</label>
                            <select id="interviewer_id" name="interviewer_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('interviewer_id') border-red-500 @enderror">
                                <option value="">Select Interviewer</option>
                                @foreach($interviewers as $interviewer)
                                    <option value="{{ $interviewer->id }}" {{ old('interviewer_id', $assessmentInterview->interviewer_id) == $interviewer->id ? 'selected' : '' }}>
                                        {{ $interviewer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('interviewer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Title -->
                        <div class="md:col-span-2">
                            <label for="session_title" class="block text-sm font-semibold text-gray-700 mb-2">Session Title *</label>
                            <input type="text" id="session_title" name="session_title" value="{{ old('session_title', $assessmentInterview->session_title) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('session_title') border-red-500 @enderror">
                            @error('session_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Purpose -->
                        <div class="md:col-span-2">
                            <label for="purpose" class="block text-sm font-semibold text-gray-700 mb-2">Purpose</label>
                            <textarea id="purpose" name="purpose" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('purpose') border-red-500 @enderror">{{ old('purpose', $assessmentInterview->purpose) }}</textarea>
                            @error('purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conducted At -->
                        <div>
                            <label for="conducted_at" class="block text-sm font-semibold text-gray-700 mb-2">Interview Date *</label>
                            <input type="datetime-local" id="conducted_at" name="conducted_at" value="{{ old('conducted_at', $assessmentInterview->conducted_at?->format('Y-m-d\TH:i')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('conducted_at') border-red-500 @enderror">
                            @error('conducted_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">Duration (minutes)</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $assessmentInterview->duration_minutes) }}" min="1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('duration_minutes') border-red-500 @enderror">
                            @error('duration_minutes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $assessmentInterview->location) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Interview Method -->
                        <div>
                            <label for="interview_method" class="block text-sm font-semibold text-gray-700 mb-2">Interview Method *</label>
                            <select id="interview_method" name="interview_method" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('interview_method') border-red-500 @enderror">
                                <option value="face_to_face" {{ old('interview_method', $assessmentInterview->interview_method) === 'face_to_face' ? 'selected' : '' }}>Face to Face</option>
                                <option value="video_conference" {{ old('interview_method', $assessmentInterview->interview_method) === 'video_conference' ? 'selected' : '' }}>Video Conference</option>
                                <option value="phone" {{ old('interview_method', $assessmentInterview->interview_method) === 'phone' ? 'selected' : '' }}>Phone</option>
                                <option value="written" {{ old('interview_method', $assessmentInterview->interview_method) === 'written' ? 'selected' : '' }}>Written</option>
                            </select>
                            @error('interview_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Assessment Findings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Findings</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Key Findings -->
                        <div>
                            <label for="key_findings" class="block text-sm font-semibold text-gray-700 mb-2">Key Findings</label>
                            <textarea id="key_findings" name="key_findings" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('key_findings') border-red-500 @enderror">{{ old('key_findings', $assessmentInterview->key_findings) }}</textarea>
                            @error('key_findings')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Competencies Demonstrated -->
                        <div>
                            <label for="competencies_demonstrated" class="block text-sm font-semibold text-gray-700 mb-2">Competencies Demonstrated</label>
                            <textarea id="competencies_demonstrated" name="competencies_demonstrated" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('competencies_demonstrated') border-red-500 @enderror">{{ old('competencies_demonstrated', $assessmentInterview->competencies_demonstrated) }}</textarea>
                            @error('competencies_demonstrated')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaps Identified -->
                        <div>
                            <label for="gaps_identified" class="block text-sm font-semibold text-gray-700 mb-2">Gaps Identified</label>
                            <textarea id="gaps_identified" name="gaps_identified" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('gaps_identified') border-red-500 @enderror">{{ old('gaps_identified', $assessmentInterview->gaps_identified) }}</textarea>
                            @error('gaps_identified')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Assessment Results -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Results</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Overall Assessment -->
                        <div>
                            <label for="overall_assessment" class="block text-sm font-semibold text-gray-700 mb-2">Overall Assessment</label>
                            <select id="overall_assessment" name="overall_assessment"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('overall_assessment') border-red-500 @enderror">
                                <option value="">Not Assessed</option>
                                <option value="fully_satisfactory" {{ old('overall_assessment', $assessmentInterview->overall_assessment) === 'fully_satisfactory' ? 'selected' : '' }}>Fully Satisfactory</option>
                                <option value="satisfactory" {{ old('overall_assessment', $assessmentInterview->overall_assessment) === 'satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                                <option value="needs_improvement" {{ old('overall_assessment', $assessmentInterview->overall_assessment) === 'needs_improvement' ? 'selected' : '' }}>Needs Improvement</option>
                                <option value="unsatisfactory" {{ old('overall_assessment', $assessmentInterview->overall_assessment) === 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                            </select>
                            @error('overall_assessment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Score -->
                        <div>
                            <label for="score" class="block text-sm font-semibold text-gray-700 mb-2">Score (0-100)</label>
                            <input type="number" id="score" name="score" value="{{ old('score', $assessmentInterview->score) }}" min="0" max="100" step="0.1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('score') border-red-500 @enderror">
                            @error('score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Observations -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Observations</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Behavioral Observations -->
                        <div>
                            <label for="behavioral_observations" class="block text-sm font-semibold text-gray-700 mb-2">Behavioral Observations</label>
                            <textarea id="behavioral_observations" name="behavioral_observations" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('behavioral_observations') border-red-500 @enderror"
                                placeholder="Communication skills, confidence, professionalism, etc.">{{ old('behavioral_observations', $assessmentInterview->behavioral_observations) }}</textarea>
                            @error('behavioral_observations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Technical Observations -->
                        <div>
                            <label for="technical_observations" class="block text-sm font-semibold text-gray-700 mb-2">Technical Observations</label>
                            <textarea id="technical_observations" name="technical_observations" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('technical_observations') border-red-500 @enderror"
                                placeholder="Technical knowledge demonstrated, problem-solving skills, etc.">{{ old('technical_observations', $assessmentInterview->technical_observations) }}</textarea>
                            @error('technical_observations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Interviewer Notes -->
                        <div>
                            <label for="interviewer_notes" class="block text-sm font-semibold text-gray-700 mb-2">Interviewer Notes</label>
                            <textarea id="interviewer_notes" name="interviewer_notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('interviewer_notes') border-red-500 @enderror">{{ old('interviewer_notes', $assessmentInterview->interviewer_notes) }}</textarea>
                            @error('interviewer_notes')
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
                                <input type="checkbox" id="requires_follow_up" name="requires_follow_up" value="1" {{ old('requires_follow_up', $assessmentInterview->requires_follow_up) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Requires Follow-up Action</span>
                            </label>
                        </div>

                        <!-- Follow-up Items -->
                        <div>
                            <label for="follow_up_items" class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Items</label>
                            <textarea id="follow_up_items" name="follow_up_items" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('follow_up_items') border-red-500 @enderror">{{ old('follow_up_items', $assessmentInterview->follow_up_items) }}</textarea>
                            @error('follow_up_items')
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

                        <a href="{{ route('admin.assessment-interviews.show', $assessmentInterview) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
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
                                <li>• Document both behavioral and technical observations</li>
                                <li>• Note specific examples of competencies demonstrated</li>
                                <li>• Identify gaps that require follow-up actions</li>
                                <li>• Be objective and specific in your assessment</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
