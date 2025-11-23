@extends('layouts.admin')

@section('title', 'Edit Assessment')

@php
    $active = 'assessments';
@endphp

@section('page_title', 'Edit Assessment')
@section('page_description', 'Update assessment information')

@section('content')
    <form action="{{ route('admin.assessments.update', $assessment) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $assessment->title) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror">{{ old('description', $assessment->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Assessment Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Assessee -->
                        <div>
                            <label for="assessee_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessee *</label>
                            <select id="assessee_id" name="assessee_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessee_id') border-red-500 @enderror">
                                <option value="">Select Assessee</option>
                                @foreach($assessees as $assessee)
                                    <option value="{{ $assessee->id }}" {{ old('assessee_id', $assessment->assessee_id) == $assessee->id ? 'selected' : '' }}>
                                        {{ $assessee->full_name }} - {{ $assessee->assessee_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheme -->
                        <div>
                            <label for="scheme_id" class="block text-sm font-semibold text-gray-700 mb-2">Certification Scheme *</label>
                            <select id="scheme_id" name="scheme_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheme_id') border-red-500 @enderror">
                                <option value="">Select Scheme</option>
                                @foreach($schemes as $scheme)
                                    <option value="{{ $scheme->id }}" {{ old('scheme_id', $assessment->scheme_id) == $scheme->id ? 'selected' : '' }}>
                                        {{ $scheme->name }} - {{ $scheme->code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('scheme_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lead Assessor -->
                        <div>
                            <label for="lead_assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Lead Assessor *</label>
                            <select id="lead_assessor_id" name="lead_assessor_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('lead_assessor_id') border-red-500 @enderror">
                                <option value="">Select Assessor</option>
                                @foreach($assessors as $assessor)
                                    <option value="{{ $assessor->id }}" {{ old('lead_assessor_id', $assessment->lead_assessor_id) == $assessor->id ? 'selected' : '' }}>
                                        {{ $assessor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lead_assessor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event -->
                        <div>
                            <label for="event_id" class="block text-sm font-semibold text-gray-700 mb-2">Event (Optional)</label>
                            <select id="event_id" name="event_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('event_id') border-red-500 @enderror">
                                <option value="">Select Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id', $assessment->event_id) == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assessment Type -->
                        <div>
                            <label for="assessment_type" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Type *</label>
                            <select id="assessment_type" name="assessment_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="initial" {{ old('assessment_type', $assessment->assessment_type) === 'initial' ? 'selected' : '' }}>Initial Assessment</option>
                                <option value="verification" {{ old('assessment_type', $assessment->assessment_type) === 'verification' ? 'selected' : '' }}>Verification</option>
                                <option value="surveillance" {{ old('assessment_type', $assessment->assessment_type) === 'surveillance' ? 'selected' : '' }}>Surveillance</option>
                                <option value="re_assessment" {{ old('assessment_type', $assessment->assessment_type) === 're_assessment' ? 'selected' : '' }}>Re-Assessment</option>
                            </select>
                            @error('assessment_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assessment Method -->
                        <div>
                            <label for="assessment_method" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Method *</label>
                            <select id="assessment_method" name="assessment_method" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_method') border-red-500 @enderror">
                                <option value="">Select Method</option>
                                <option value="portfolio" {{ old('assessment_method', $assessment->assessment_method) === 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                                <option value="observation" {{ old('assessment_method', $assessment->assessment_method) === 'observation' ? 'selected' : '' }}>Observation</option>
                                <option value="interview" {{ old('assessment_method', $assessment->assessment_method) === 'interview' ? 'selected' : '' }}>Interview</option>
                                <option value="demonstration" {{ old('assessment_method', $assessment->assessment_method) === 'demonstration' ? 'selected' : '' }}>Demonstration</option>
                                <option value="written_test" {{ old('assessment_method', $assessment->assessment_method) === 'written_test' ? 'selected' : '' }}>Written Test</option>
                                <option value="mixed" {{ old('assessment_method', $assessment->assessment_method) === 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                            @error('assessment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Schedule Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Scheduled Date -->
                        <div>
                            <label for="scheduled_date" class="block text-sm font-semibold text-gray-700 mb-2">Scheduled Date *</label>
                            <input type="date" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date', $assessment->scheduled_date?->format('Y-m-d')) }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheduled_date') border-red-500 @enderror">
                            @error('scheduled_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheduled Time -->
                        <div>
                            <label for="scheduled_time" class="block text-sm font-semibold text-gray-700 mb-2">Scheduled Time</label>
                            <input type="time" id="scheduled_time" name="scheduled_time" value="{{ old('scheduled_time', $assessment->scheduled_time?->format('H:i')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheduled_time') border-red-500 @enderror">
                            @error('scheduled_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- TUK -->
                        <div>
                            <label for="tuk_id" class="block text-sm font-semibold text-gray-700 mb-2">TUK (Tempat Uji Kompetensi)</label>
                            <select id="tuk_id" name="tuk_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tuk_id') border-red-500 @enderror">
                                <option value="">Select TUK</option>
                                @foreach($tuks as $tuk)
                                    <option value="{{ $tuk->id }}" {{ old('tuk_id', $assessment->tuk_id) == $tuk->id ? 'selected' : '' }}>
                                        {{ $tuk->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tuk_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Venue -->
                        <div>
                            <label for="venue" class="block text-sm font-semibold text-gray-700 mb-2">Venue</label>
                            <input type="text" id="venue" name="venue" value="{{ old('venue', $assessment->venue) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('venue') border-red-500 @enderror">
                            @error('venue')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Planned Duration -->
                        <div class="md:col-span-2">
                            <label for="planned_duration_minutes" class="block text-sm font-semibold text-gray-700 mb-2">Planned Duration (minutes)</label>
                            <input type="number" id="planned_duration_minutes" name="planned_duration_minutes" value="{{ old('planned_duration_minutes', $assessment->planned_duration_minutes) }}" min="1"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('planned_duration_minutes') border-red-500 @enderror">
                            @error('planned_duration_minutes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Preparation Notes -->
                        <div>
                            <label for="preparation_notes" class="block text-sm font-semibold text-gray-700 mb-2">Preparation Notes</label>
                            <textarea id="preparation_notes" name="preparation_notes" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('preparation_notes') border-red-500 @enderror">{{ old('preparation_notes', $assessment->preparation_notes) }}</textarea>
                            @error('preparation_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Requirements -->
                        <div>
                            <label for="special_requirements" class="block text-sm font-semibold text-gray-700 mb-2">Special Requirements</label>
                            <textarea id="special_requirements" name="special_requirements" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('special_requirements') border-red-500 @enderror">{{ old('special_requirements', $assessment->special_requirements) }}</textarea>
                            @error('special_requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">General Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror">{{ old('notes', $assessment->notes) }}</textarea>
                            @error('notes')
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
                            Update Assessment
                        </button>

                        <a href="{{ route('admin.assessments.show', $assessment) }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">
                            Cancel
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Assessment Number:</span><br>
                            {{ $assessment->assessment_number }}
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            <span class="font-semibold">Status:</span><br>
                            {{ ucwords(str_replace('_', ' ', $assessment->status)) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
