@extends('layouts.admin')

@section('title', 'Create Assessor Review')

@php
    $active = 'apl02-reviews';
@endphp

@section('page_title', 'Create New Assessor Review')
@section('page_description', 'Create a new APL-02 portfolio assessor review')

@section('content')
    <form action="{{ route('admin.apl02.reviews.store') }}" method="POST" class="w-full">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Review Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Review Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Portfolio Unit -->
                        <div>
                            <label for="apl02_unit_id" class="block text-sm font-semibold text-gray-700 mb-2">Portfolio Unit *</label>
                            <select id="apl02_unit_id" name="apl02_unit_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('apl02_unit_id') border-red-500 @enderror">
                                <option value="">Select Portfolio Unit</option>
                                @foreach($units as $unitItem)
                                    <option value="{{ $unitItem->id }}" {{ old('apl02_unit_id', $unit?->id) == $unitItem->id ? 'selected' : '' }}>
                                        {{ $unitItem->unit_code }} - {{ $unitItem->assessee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('apl02_unit_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($unit)
                                <p class="mt-1 text-xs text-gray-600">Pre-selected from portfolio unit</p>
                            @endif
                        </div>

                        <!-- Assessor -->
                        <div>
                            <label for="assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessor *</label>
                            <select id="assessor_id" name="assessor_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_id') border-red-500 @enderror">
                                <option value="">Select Assessor</option>
                                @foreach($assessors as $assessor)
                                    <option value="{{ $assessor->id }}" {{ old('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                        {{ $assessor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Review Type -->
                        <div>
                            <label for="review_type" class="block text-sm font-semibold text-gray-700 mb-2">Review Type *</label>
                            <select id="review_type" name="review_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('review_type') border-red-500 @enderror">
                                <option value="">Select Review Type</option>
                                <option value="initial_review" {{ old('review_type') === 'initial_review' ? 'selected' : '' }}>Initial Review</option>
                                <option value="verification" {{ old('review_type') === 'verification' ? 'selected' : '' }}>Verification</option>
                                <option value="validation" {{ old('review_type') === 'validation' ? 'selected' : '' }}>Validation</option>
                                <option value="final_assessment" {{ old('review_type') === 'final_assessment' ? 'selected' : '' }}>Final Assessment</option>
                                <option value="re_assessment" {{ old('review_type') === 're_assessment' ? 'selected' : '' }}>Re-assessment</option>
                            </select>
                            @error('review_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div>
                            <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-2">Deadline (Optional)</label>
                            <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('deadline') border-red-500 @enderror">
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Unit Preview (if unit is selected) -->
                @if($unit)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Portfolio Unit Preview</h3>

                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Code</label>
                                    <p class="text-gray-900">{{ $unit->unit_code }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Assessee</label>
                                    <p class="text-gray-900">{{ $unit->assessee->full_name }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Unit Title</label>
                                <p class="text-gray-900">{{ $unit->unit_title }}</p>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                                    <p class="text-sm text-gray-900">{{ $unit->status_label }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Progress</label>
                                    <p class="text-sm text-gray-900">{{ number_format($unit->completion_percentage, 0) }}%</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Evidence</label>
                                    <p class="text-sm text-gray-900">{{ $unit->total_evidence }} items</p>
                                </div>
                            </div>

                            @if($unit->schemeUnit && $unit->schemeUnit->elements->isNotEmpty())
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Competency Elements</label>
                                    <div class="space-y-1">
                                        @foreach($unit->schemeUnit->elements as $element)
                                            <div class="text-sm text-gray-700 flex items-start gap-2">
                                                <span class="material-symbols-outlined text-blue-600 text-sm mt-0.5">check_circle</span>
                                                <span>{{ $element->code }} - {{ $element->title }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <div class="space-y-3">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">save</span>
                            <span>Create Review</span>
                        </button>

                        <a href="{{ route('admin.apl02.reviews.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Note:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Review number will be auto-generated</li>
                            <li>• Review will be created in "Draft" status</li>
                            <li>• You can conduct the review after creation</li>
                            <li>• Assessor can complete the review and submit decision</li>
                        </ul>
                    </div>

                    @if($unit)
                        <div class="mt-4 p-4 bg-green-50 rounded-lg">
                            <p class="text-xs text-green-800 mb-2 font-semibold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">info</span>
                                Quick Review
                            </p>
                            <p class="text-xs text-green-700">
                                This review is being created for unit <strong>{{ $unit->unit_code }}</strong>. After creation, you'll be able to conduct the assessment immediately.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection
