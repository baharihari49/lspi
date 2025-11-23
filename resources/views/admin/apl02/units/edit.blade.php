@extends('layouts.admin')

@section('title', 'Edit APL-02 Portfolio Unit')

@php
    $active = 'apl02-units';
@endphp

@section('page_title', 'Edit Portfolio Unit')
@section('page_description', 'Update APL-02 portfolio unit assessment')

@section('content')
    <form action="{{ route('admin.apl02.units.update', $unit) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Unit Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Unit Information</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Assessee -->
                        <div>
                            <label for="assessee_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessee *</label>
                            <select id="assessee_id" name="assessee_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessee_id') border-red-500 @enderror">
                                <option value="">Select Assessee</option>
                                @foreach($assessees as $assessee)
                                    <option value="{{ $assessee->id }}" {{ old('assessee_id', $unit->assessee_id) == $assessee->id ? 'selected' : '' }}>
                                        {{ $assessee->full_name }} ({{ $assessee->registration_number }})
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
                                    <option value="{{ $scheme->id }}" {{ old('scheme_id', $unit->scheme_id) == $scheme->id ? 'selected' : '' }}>
                                        {{ $scheme->name }} ({{ $scheme->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('scheme_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheme Unit -->
                        <div>
                            <label for="scheme_unit_id" class="block text-sm font-semibold text-gray-700 mb-2">Competency Unit *</label>
                            <select id="scheme_unit_id" name="scheme_unit_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheme_unit_id') border-red-500 @enderror">
                                <option value="">Select Scheme First</option>
                                @if($schemeUnits)
                                    @foreach($schemeUnits as $schemeUnit)
                                        <option value="{{ $schemeUnit->id }}" {{ old('scheme_unit_id', $unit->scheme_unit_id) == $schemeUnit->id ? 'selected' : '' }}>
                                            {{ $schemeUnit->code }} - {{ $schemeUnit->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('scheme_unit_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event (Optional) -->
                        <div>
                            <label for="event_id" class="block text-sm font-semibold text-gray-700 mb-2">Event (Optional)</label>
                            <select id="event_id" name="event_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('event_id') border-red-500 @enderror">
                                <option value="">Not associated with any event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id', $unit->event_id) == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }} ({{ $event->start_date->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assessor (Optional) -->
                        <div>
                            <label for="assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessor (Optional)</label>
                            <select id="assessor_id" name="assessor_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_id') border-red-500 @enderror">
                                <option value="">Not assigned yet</option>
                                @foreach($assessors as $assessor)
                                    <option value="{{ $assessor->id }}" {{ old('assessor_id', $unit->assessor_id) == $assessor->id ? 'selected' : '' }}>
                                        {{ $assessor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select id="status" name="status"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('status') border-red-500 @enderror">
                                <option value="not_started" {{ old('status', $unit->status) === 'not_started' ? 'selected' : '' }}>Not Started</option>
                                <option value="in_progress" {{ old('status', $unit->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="submitted" {{ old('status', $unit->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="under_review" {{ old('status', $unit->status) === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="competent" {{ old('status', $unit->status) === 'competent' ? 'selected' : '' }}>Competent</option>
                                <option value="not_yet_competent" {{ old('status', $unit->status) === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                                <option value="completed" {{ old('status', $unit->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assessment Result -->
                        <div>
                            <label for="assessment_result" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Result</label>
                            <select id="assessment_result" name="assessment_result"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_result') border-red-500 @enderror">
                                <option value="pending" {{ old('assessment_result', $unit->assessment_result) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="competent" {{ old('assessment_result', $unit->assessment_result) === 'competent' ? 'selected' : '' }}>Competent</option>
                                <option value="not_yet_competent" {{ old('assessment_result', $unit->assessment_result) === 'not_yet_competent' ? 'selected' : '' }}>Not Yet Competent</option>
                                <option value="requires_more_evidence" {{ old('assessment_result', $unit->assessment_result) === 'requires_more_evidence' ? 'selected' : '' }}>Requires More Evidence</option>
                            </select>
                            @error('assessment_result')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Notes</h3>

                    <div class="space-y-4">
                        <!-- Assessment Notes -->
                        <div>
                            <label for="assessment_notes" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Notes</label>
                            <textarea id="assessment_notes" name="assessment_notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_notes') border-red-500 @enderror">{{ old('assessment_notes', $unit->assessment_notes) }}</textarea>
                            @error('assessment_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recommendations -->
                        <div>
                            <label for="recommendations" class="block text-sm font-semibold text-gray-700 mb-2">Recommendations</label>
                            <textarea id="recommendations" name="recommendations" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('recommendations') border-red-500 @enderror">{{ old('recommendations', $unit->recommendations) }}</textarea>
                            @error('recommendations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
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
                            <span>Update Unit</span>
                        </button>

                        <a href="{{ route('admin.apl02.units.show', $unit) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">visibility</span>
                            <span>View Details</span>
                        </a>

                        <a href="{{ route('admin.apl02.units.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Unit Info:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Code: {{ $unit->unit_code }}</li>
                            <li>• Progress: {{ number_format($unit->completion_percentage, 0) }}%</li>
                            <li>• Evidence: {{ $unit->total_evidence }} items</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Load scheme units when scheme is selected
        document.getElementById('scheme_id').addEventListener('change', function() {
            const schemeId = this.value;
            const unitSelect = document.getElementById('scheme_unit_id');

            // Clear current options
            unitSelect.innerHTML = '<option value="">Loading...</option>';

            if (!schemeId) {
                unitSelect.innerHTML = '<option value="">Select Scheme First</option>';
                return;
            }

            // Fetch scheme units
            fetch(`/admin/apl02/api/scheme-units?scheme_id=${schemeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.units.length > 0) {
                        unitSelect.innerHTML = '<option value="">Select Competency Unit</option>';
                        data.units.forEach(unit => {
                            const option = document.createElement('option');
                            option.value = unit.id;
                            option.textContent = `${unit.code} - ${unit.title}`;
                            if ('{{ old('scheme_unit_id', $unit->scheme_unit_id) }}' == unit.id) {
                                option.selected = true;
                            }
                            unitSelect.appendChild(option);
                        });
                    } else {
                        unitSelect.innerHTML = '<option value="">No units available for this scheme</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    unitSelect.innerHTML = '<option value="">Error loading units</option>';
                });
        });

        // Trigger change on page load if scheme is pre-selected
        window.addEventListener('DOMContentLoaded', function() {
            const schemeSelect = document.getElementById('scheme_id');
            if (schemeSelect.value) {
                schemeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
