@extends('layouts.admin')

@section('title', 'Create APL-02 Portfolio Unit')

@php
    $active = 'apl02-units';
@endphp

@section('page_title', 'Create New Portfolio Unit')
@section('page_description', 'Add a new APL-02 portfolio unit assessment')

@section('content')
    <form action="{{ route('admin.apl02.units.store') }}" method="POST" class="w-full">
        @csrf

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
                                    <option value="{{ $assessee->id }}" {{ old('assessee_id') == $assessee->id ? 'selected' : '' }}>
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
                                    <option value="{{ $scheme->id }}" {{ old('scheme_id') == $scheme->id ? 'selected' : '' }}>
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
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
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
                                    <option value="{{ $assessor->id }}" {{ old('assessor_id') == $assessor->id ? 'selected' : '' }}>
                                        {{ $assessor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Timeline (Optional) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline (Optional)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Target Start Date -->
                        <div>
                            <label for="target_start_date" class="block text-sm font-semibold text-gray-700 mb-2">Target Start Date</label>
                            <input type="date" id="target_start_date" name="target_start_date" value="{{ old('target_start_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('target_start_date') border-red-500 @enderror">
                            @error('target_start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Completion Date -->
                        <div>
                            <label for="target_completion_date" class="block text-sm font-semibold text-gray-700 mb-2">Target Completion Date</label>
                            <input type="date" id="target_completion_date" name="target_completion_date" value="{{ old('target_completion_date') }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('target_completion_date') border-red-500 @enderror">
                            @error('target_completion_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Information</h3>

                    <div class="space-y-4">
                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
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
                            <span>Create Unit</span>
                        </button>

                        <a href="{{ route('admin.apl02.units.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-800 mb-2 font-semibold">Note:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Unit will be created with "Not Started" status</li>
                            <li>• You can add evidence after creation</li>
                            <li>• Progress will be calculated automatically</li>
                            <li>• Assessor can be assigned later</li>
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
                            option.textContent = `${unit.unit_code} - ${unit.unit_title}`;
                            if ('{{ old('scheme_unit_id') }}' == unit.id) {
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
