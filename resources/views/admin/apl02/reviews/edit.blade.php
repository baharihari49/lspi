@extends('layouts.admin')

@section('title', 'Edit Review')

@php
    $active = 'apl02-reviews';
@endphp

@section('page_title', 'Edit APL-02 Review')
@section('page_description', 'Update review information')

@section('content')
    <form action="{{ route('admin.apl02.reviews.update', $review) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Review Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Review Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Review Number (Read-only) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Review Number</label>
                            <input type="text" value="{{ $review->review_number }}" readonly
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-600">Auto-generated, cannot be changed</p>
                        </div>

                        <!-- Portfolio Unit (Read-only) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Portfolio Unit</label>
                            <input type="text" value="{{ $review->apl02Unit->unit_code }} - {{ $review->apl02Unit->assessee->full_name }}" readonly
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-600">Portfolio unit cannot be changed</p>
                        </div>

                        <!-- Assessor (Read-only) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Assessor</label>
                            <input type="text" value="{{ $review->assessor->name }}" readonly
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-600">Assessor cannot be changed</p>
                        </div>

                        <!-- Review Type -->
                        <div>
                            <label for="review_type" class="block text-sm font-semibold text-gray-700 mb-2">Review Type *</label>
                            <select id="review_type" name="review_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('review_type') border-red-500 @enderror">
                                <option value="">Select Review Type</option>
                                <option value="initial_review" {{ old('review_type', $review->review_type) === 'initial_review' ? 'selected' : '' }}>Initial Review</option>
                                <option value="verification" {{ old('review_type', $review->review_type) === 'verification' ? 'selected' : '' }}>Verification</option>
                                <option value="validation" {{ old('review_type', $review->review_type) === 'validation' ? 'selected' : '' }}>Validation</option>
                                <option value="final_assessment" {{ old('review_type', $review->review_type) === 'final_assessment' ? 'selected' : '' }}>Final Assessment</option>
                                <option value="re_assessment" {{ old('review_type', $review->review_type) === 're_assessment' ? 'selected' : '' }}>Re-assessment</option>
                            </select>
                            @error('review_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div>
                            <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-2">Deadline</label>
                            <input type="date" id="deadline" name="deadline" value="{{ old('deadline', $review->deadline?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('deadline') border-red-500 @enderror">
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Current Status</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'submitted' => 'bg-indigo-100 text-indigo-800',
                                    'approved' => 'bg-purple-100 text-purple-800',
                                    'revision_required' => 'bg-yellow-100 text-yellow-800',
                                ];
                            @endphp
                            <span class="inline-block px-3 py-2 rounded-lg text-sm font-semibold {{ $statusColors[$review->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Decision</label>
                            @php
                                $decisionColors = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'competent' => 'bg-green-100 text-green-800',
                                    'not_yet_competent' => 'bg-red-100 text-red-800',
                                    'requires_more_evidence' => 'bg-orange-100 text-orange-800',
                                    'requires_demonstration' => 'bg-yellow-100 text-yellow-800',
                                    'deferred' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="inline-block px-3 py-2 rounded-lg text-sm font-semibold {{ $decisionColors[$review->decision] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                            </span>
                        </div>

                        @if($review->overall_score)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Overall Score</label>
                                <p class="text-2xl font-bold text-blue-900">{{ number_format($review->overall_score, 1) }}</p>
                            </div>
                        @endif

                        @if($review->review_started_at)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Started At</label>
                                <p class="text-sm text-gray-900">{{ $review->review_started_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 font-semibold">Note:</p>
                        <p class="text-xs text-blue-700 mt-1">Status and decision can only be changed through the "Conduct Review" process. Use this form to update basic review information only.</p>
                    </div>
                </div>

                <!-- Comments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Comments & Notes</h3>

                    <div class="space-y-4">
                        <!-- Overall Comments -->
                        <div>
                            <label for="overall_comments" class="block text-sm font-semibold text-gray-700 mb-2">Overall Comments</label>
                            <textarea id="overall_comments" name="overall_comments" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('overall_comments') border-red-500 @enderror">{{ old('overall_comments', $review->overall_comments) }}</textarea>
                            @error('overall_comments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recommendations -->
                        <div>
                            <label for="recommendations" class="block text-sm font-semibold text-gray-700 mb-2">Recommendations</label>
                            <textarea id="recommendations" name="recommendations" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('recommendations') border-red-500 @enderror">{{ old('recommendations', $review->recommendations) }}</textarea>
                            @error('recommendations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Portfolio Unit Preview -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Portfolio Unit Details</h3>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Code</label>
                                <p class="text-gray-900">{{ $review->apl02Unit->unit_code }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Assessee</label>
                                <p class="text-gray-900">{{ $review->apl02Unit->assessee->full_name }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Title</label>
                            <p class="text-gray-900">{{ $review->apl02Unit->unit_title }}</p>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Progress</label>
                                <p class="text-sm text-gray-900">{{ number_format($review->apl02Unit->completion_percentage, 0) }}%</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Evidence</label>
                                <p class="text-sm text-gray-900">{{ $review->apl02Unit->evidence->count() }} items</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                                <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $review->apl02Unit->status)) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.apl02.units.show', $review->apl02Unit) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                            <span class="material-symbols-outlined text-lg">open_in_new</span>
                            <span>View Full Portfolio Unit</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Update Review</span>
                        </button>

                        @if(in_array($review->status, ['draft', 'in_progress']))
                            <a href="{{ route('admin.apl02.reviews.conduct', $review) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">rate_review</span>
                                <span>Conduct Review</span>
                            </a>
                        @endif

                        <a href="{{ route('admin.apl02.reviews.show', $review) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">visibility</span>
                            <span>View Details</span>
                        </a>

                        <a href="{{ route('admin.apl02.reviews.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span>Back to List</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Editable Fields:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Review type</li>
                            <li>• Deadline</li>
                            <li>• Overall comments</li>
                            <li>• Recommendations</li>
                        </ul>
                    </div>

                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                        <p class="text-xs text-yellow-800 mb-2 font-semibold">Read-Only Fields:</p>
                        <ul class="text-xs text-yellow-700 space-y-1">
                            <li>• Review number</li>
                            <li>• Portfolio unit</li>
                            <li>• Assessor</li>
                            <li>• Status & decision</li>
                            <li>• VATUK scores</li>
                        </ul>
                        <p class="text-xs text-yellow-700 mt-2">Use "Conduct Review" to update scores and decision.</p>
                    </div>

                    <!-- Metadata -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Metadata</h4>
                        <div class="space-y-2 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>Created:</span>
                                <span>{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Updated:</span>
                                <span>{{ $review->updated_at->format('d M Y') }}</span>
                            </div>
                            @if($review->review_completed_at)
                                <div class="flex justify-between">
                                    <span>Completed:</span>
                                    <span>{{ $review->review_completed_at->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
