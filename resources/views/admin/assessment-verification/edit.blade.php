@extends('layouts.admin')

@section('title', 'Edit Verification')

@php
    $active = 'assessment-verification';
@endphp

@section('page_title', 'Edit Verification')
@section('page_description', $assessmentVerification->verification_number)

@section('content')
    <form action="{{ route('admin.assessment-verification.update', $assessmentVerification) }}" method="POST" class="w-full">
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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Verification Number</label>
                            <input type="text" value="{{ $assessmentVerification->verification_number }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment</label>
                            <input type="text" value="{{ $assessmentVerification->assessment->assessment_number }} - {{ $assessmentVerification->assessment->assessee->full_name }}" disabled
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>

                        @if($assessmentVerification->assessmentUnit)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment Unit</label>
                                <input type="text" value="{{ $assessmentVerification->assessmentUnit->unit_code }} - {{ $assessmentVerification->assessmentUnit->unit_title }}" disabled
                                    class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Verification Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Verification Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Verifier -->
                        <div class="md:col-span-2">
                            <label for="verifier_id" class="block text-sm font-semibold text-gray-700 mb-2">Verifier *</label>
                            <select id="verifier_id" name="verifier_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('verifier_id') border-red-500 @enderror">
                                <option value="">Select Verifier</option>
                                @foreach($verifiers as $verifier)
                                    <option value="{{ $verifier->id }}" {{ old('verifier_id', $assessmentVerification->verifier_id) == $verifier->id ? 'selected' : '' }}>
                                        {{ $verifier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('verifier_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Verification Level -->
                        <div>
                            <label for="verification_level" class="block text-sm font-semibold text-gray-700 mb-2">Verification Level *</label>
                            <select id="verification_level" name="verification_level" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('verification_level') border-red-500 @enderror">
                                <option value="unit_level" {{ old('verification_level', $assessmentVerification->verification_level) === 'unit_level' ? 'selected' : '' }}>Unit Level</option>
                                <option value="assessment_level" {{ old('verification_level', $assessmentVerification->verification_level) === 'assessment_level' ? 'selected' : '' }}>Assessment Level</option>
                                <option value="quality_assurance" {{ old('verification_level', $assessmentVerification->verification_level) === 'quality_assurance' ? 'selected' : '' }}>Quality Assurance</option>
                            </select>
                            @error('verification_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Verification Type -->
                        <div>
                            <label for="verification_type" class="block text-sm font-semibold text-gray-700 mb-2">Verification Type *</label>
                            <select id="verification_type" name="verification_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('verification_type') border-red-500 @enderror">
                                <option value="internal" {{ old('verification_type', $assessmentVerification->verification_type) === 'internal' ? 'selected' : '' }}>Internal</option>
                                <option value="external" {{ old('verification_type', $assessmentVerification->verification_type) === 'external' ? 'selected' : '' }}>External</option>
                                <option value="peer_review" {{ old('verification_type', $assessmentVerification->verification_type) === 'peer_review' ? 'selected' : '' }}>Peer Review</option>
                            </select>
                            @error('verification_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Verification Status -->
                        <div>
                            <label for="verification_status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select id="verification_status" name="verification_status" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('verification_status') border-red-500 @enderror">
                                <option value="pending" {{ old('verification_status', $assessmentVerification->verification_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('verification_status', $assessmentVerification->verification_status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('verification_status', $assessmentVerification->verification_status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="approved" {{ old('verification_status', $assessmentVerification->verification_status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="requires_modification" {{ old('verification_status', $assessmentVerification->verification_status) === 'requires_modification' ? 'selected' : '' }}>Requires Modification</option>
                                <option value="rejected" {{ old('verification_status', $assessmentVerification->verification_status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('verification_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Verification Result -->
                        <div>
                            <label for="verification_result" class="block text-sm font-semibold text-gray-700 mb-2">Result</label>
                            <select id="verification_result" name="verification_result"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('verification_result') border-red-500 @enderror">
                                <option value="">Not Yet Determined</option>
                                <option value="satisfactory" {{ old('verification_result', $assessmentVerification->verification_result) === 'satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                                <option value="needs_minor_changes" {{ old('verification_result', $assessmentVerification->verification_result) === 'needs_minor_changes' ? 'selected' : '' }}>Needs Minor Changes</option>
                                <option value="needs_major_changes" {{ old('verification_result', $assessmentVerification->verification_result) === 'needs_major_changes' ? 'selected' : '' }}>Needs Major Changes</option>
                                <option value="unsatisfactory" {{ old('verification_result', $assessmentVerification->verification_result) === 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory</option>
                            </select>
                            @error('verification_result')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Verified At -->
                        <div>
                            <label for="verified_at" class="block text-sm font-semibold text-gray-700 mb-2">Verified At</label>
                            <input type="datetime-local" id="verified_at" name="verified_at" value="{{ old('verified_at', $assessmentVerification->verified_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('verified_at') border-red-500 @enderror">
                            @error('verified_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="verification_duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">Duration (minutes)</label>
                            <input type="number" id="verification_duration_minutes" name="verification_duration_minutes" value="{{ old('verification_duration_minutes', $assessmentVerification->verification_duration_minutes) }}" min="1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('verification_duration_minutes') border-red-500 @enderror">
                            @error('verification_duration_minutes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Compliance Check -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Compliance Check</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Meets Standards -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="meets_standards" value="0">
                                <input type="checkbox" id="meets_standards" name="meets_standards" value="1" {{ old('meets_standards', $assessmentVerification->meets_standards) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Meets Standards</span>
                            </label>
                        </div>

                        <!-- Evidence Sufficient -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="evidence_sufficient" value="0">
                                <input type="checkbox" id="evidence_sufficient" name="evidence_sufficient" value="1" {{ old('evidence_sufficient', $assessmentVerification->evidence_sufficient) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Evidence Sufficient</span>
                            </label>
                        </div>

                        <!-- Assessment Fair -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="assessment_fair" value="0">
                                <input type="checkbox" id="assessment_fair" name="assessment_fair" value="1" {{ old('assessment_fair', $assessmentVerification->assessment_fair) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Assessment Fair</span>
                            </label>
                        </div>

                        <!-- Documentation Complete -->
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="documentation_complete" value="0">
                                <input type="checkbox" id="documentation_complete" name="documentation_complete" value="1" {{ old('documentation_complete', $assessmentVerification->documentation_complete) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Documentation Complete</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Findings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Findings</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Findings -->
                        <div>
                            <label for="findings" class="block text-sm font-semibold text-gray-700 mb-2">Overall Findings</label>
                            <textarea id="findings" name="findings" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('findings') border-red-500 @enderror">{{ old('findings', $assessmentVerification->findings) }}</textarea>
                            @error('findings')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Strengths -->
                        <div>
                            <label for="strengths" class="block text-sm font-semibold text-gray-700 mb-2">Strengths</label>
                            <textarea id="strengths" name="strengths" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('strengths') border-red-500 @enderror">{{ old('strengths', $assessmentVerification->strengths) }}</textarea>
                            @error('strengths')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Concerns -->
                        <div>
                            <label for="concerns" class="block text-sm font-semibold text-gray-700 mb-2">Concerns</label>
                            <textarea id="concerns" name="concerns" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('concerns') border-red-500 @enderror">{{ old('concerns', $assessmentVerification->concerns) }}</textarea>
                            @error('concerns')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Areas for Improvement -->
                        <div>
                            <label for="areas_for_improvement" class="block text-sm font-semibold text-gray-700 mb-2">Areas for Improvement</label>
                            <textarea id="areas_for_improvement" name="areas_for_improvement" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('areas_for_improvement') border-red-500 @enderror">{{ old('areas_for_improvement', $assessmentVerification->areas_for_improvement) }}</textarea>
                            @error('areas_for_improvement')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Notes</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Verifier Notes -->
                        <div>
                            <label for="verifier_notes" class="block text-sm font-semibold text-gray-700 mb-2">Verifier Notes</label>
                            <textarea id="verifier_notes" name="verifier_notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('verifier_notes') border-red-500 @enderror">{{ old('verifier_notes', $assessmentVerification->verifier_notes) }}</textarea>
                            @error('verifier_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assessor Response -->
                        <div>
                            <label for="assessor_response" class="block text-sm font-semibold text-gray-700 mb-2">Assessor Response</label>
                            <textarea id="assessor_response" name="assessor_response" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_response') border-red-500 @enderror">{{ old('assessor_response', $assessmentVerification->assessor_response) }}</textarea>
                            @error('assessor_response')
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

                        <a href="{{ route('admin.assessment-verification.show', $assessmentVerification) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
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
                                <li>• Be thorough and objective in your verification</li>
                                <li>• Check all compliance requirements</li>
                                <li>• Document all findings clearly</li>
                                <li>• Provide constructive feedback</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
