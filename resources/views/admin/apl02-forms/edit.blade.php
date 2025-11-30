@extends('layouts.admin')

@section('title', 'Edit APL-02 Form - ' . $apl02Form->form_number)

@php
    $active = 'apl02-forms';
@endphp

@section('page_title', 'Edit APL-02 Form')
@section('page_description', $apl02Form->form_number)

@section('content')
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <form action="{{ route('admin.apl02-forms.update', $apl02Form) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Form Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Form Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Form Number</label>
                            <p class="text-gray-900 font-semibold">{{ $apl02Form->form_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assessee</label>
                            <p class="text-gray-900">{{ $apl02Form->assessee->full_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scheme</label>
                            <p class="text-gray-900">{{ $apl02Form->scheme->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                            <p class="text-gray-900">{{ $apl02Form->event->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" id="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="draft" {{ $apl02Form->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ $apl02Form->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="under_review" {{ $apl02Form->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="revision_required" {{ $apl02Form->status === 'revision_required' ? 'selected' : '' }}>Revision Required</option>
                                <option value="approved" {{ $apl02Form->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $apl02Form->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ $apl02Form->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div>
                            <label for="assessor_id" class="block text-sm font-medium text-gray-700 mb-1">Assessor</label>
                            <select name="assessor_id" id="assessor_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">-- Select Assessor --</option>
                                @foreach($assessors as $assessor)
                                    <option value="{{ $assessor->id }}" {{ $apl02Form->assessor_id == $assessor->id ? 'selected' : '' }}>
                                        {{ $assessor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="assessment_result" class="block text-sm font-medium text-gray-700 mb-1">Assessment Result</label>
                            <select name="assessment_result" id="assessment_result" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="pending" {{ $apl02Form->assessment_result === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="competent" {{ $apl02Form->assessment_result === 'competent' ? 'selected' : '' }}>Competent</option>
                                <option value="not_yet_competent" {{ $apl02Form->assessment_result === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Assessor Feedback -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Feedback</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="assessor_notes" class="block text-sm font-medium text-gray-700 mb-1">Assessor Notes</label>
                            <textarea name="assessor_notes" id="assessor_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Internal notes for assessment...">{{ old('assessor_notes', $apl02Form->assessor_notes) }}</textarea>
                        </div>

                        <div>
                            <label for="assessor_feedback" class="block text-sm font-medium text-gray-700 mb-1">Feedback for Assessee</label>
                            <textarea name="assessor_feedback" id="assessor_feedback" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Feedback that will be shown to assessee...">{{ old('assessor_feedback', $apl02Form->assessor_feedback) }}</textarea>
                        </div>

                        <div>
                            <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-1">Recommendations</label>
                            <textarea name="recommendations" id="recommendations" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Recommendations for improvement...">{{ old('recommendations', $apl02Form->recommendations) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Admin Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Admin Notes</h3>

                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Internal Notes (Admin Only)</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Internal notes for admin reference...">{{ old('admin_notes', $apl02Form->admin_notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Save Changes
                        </button>

                        <a href="{{ route('admin.apl02-forms.show', $apl02Form) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-xs text-gray-600">
                            <span class="font-semibold">Created:</span><br>
                            {{ $apl02Form->created_at->format('d M Y H:i') }}
                        </p>
                        @if($apl02Form->updated_at != $apl02Form->created_at)
                            <p class="text-xs text-gray-600 mt-2">
                                <span class="font-semibold">Last Updated:</span><br>
                                {{ $apl02Form->updated_at->format('d M Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Current Self Assessment (Read Only) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Self Assessment Summary</h3>

                    @if($apl02Form->self_assessment && count($apl02Form->self_assessment) > 0)
                        @php
                            $totalUnits = count($apl02Form->self_assessment);
                            $competentCount = collect($apl02Form->self_assessment)->where('is_competent', true)->count();
                            $notCompetentCount = collect($apl02Form->self_assessment)->where('is_competent', false)->count();
                            $notAnsweredCount = collect($apl02Form->self_assessment)->whereNull('is_competent')->count();
                        @endphp
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Total Units</span>
                                <span class="font-semibold text-gray-900">{{ $totalUnits }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Self-declared Competent</span>
                                <span class="font-semibold text-green-600">{{ $competentCount }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Self-declared Not Competent</span>
                                <span class="font-semibold text-red-600">{{ $notCompetentCount }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Not Answered</span>
                                <span class="font-semibold text-gray-600">{{ $notAnsweredCount }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No self assessment data</p>
                    @endif
                </div>

                <!-- Evidence Files Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Evidence Files</h3>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total Files</span>
                        <span class="font-semibold text-gray-900">{{ count($apl02Form->evidence_files ?? []) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
