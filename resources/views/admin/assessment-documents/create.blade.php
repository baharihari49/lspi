@extends('layouts.admin')

@section('title', 'Upload Document')

@php
    $active = 'assessment-documents';
@endphp

@section('page_title', 'Upload Assessment Document')
@section('page_description', 'Upload evidence and supporting documents')

@section('content')
    <form action="{{ route('admin.assessment-documents.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Document Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Document Information</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Document Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('title') border-red-500 @enderror">
                            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Assessment Linking -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Link to Assessment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="assessment_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessment *</label>
                            <select id="assessment_id" name="assessment_id" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessment_id') border-red-500 @enderror">
                                <option value="">Select Assessment</option>
                                @foreach($assessments as $assessment)
                                    <option value="{{ $assessment->id }}" {{ old('assessment_id', $selectedAssessment?->id) == $assessment->id ? 'selected' : '' }}>
                                        {{ $assessment->assessment_number }} - {{ $assessment->assessee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assessment_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="assessment_unit_id" class="block text-sm font-semibold text-gray-700 mb-2">Unit (Optional)</label>
                            <select id="assessment_unit_id" name="assessment_unit_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select Unit</option>
                            </select>
                        </div>
                        <div>
                            <label for="assessment_criteria_id" class="block text-sm font-semibold text-gray-700 mb-2">Criteria (Optional)</label>
                            <select id="assessment_criteria_id" name="assessment_criteria_id"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select Criteria</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Document Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Document Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="document_type" class="block text-sm font-semibold text-gray-700 mb-2">Document Type *</label>
                            <select id="document_type" name="document_type" required
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select Type</option>
                                <option value="evidence">Evidence</option>
                                <option value="certificate">Certificate</option>
                                <option value="report">Report</option>
                                <option value="photo">Photo</option>
                                <option value="video">Video</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="evidence_type" class="block text-sm font-semibold text-gray-700 mb-2">Evidence Type</label>
                            <select id="evidence_type" name="evidence_type"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="">Select Evidence Type</option>
                                <option value="direct">Direct Evidence</option>
                                <option value="indirect">Indirect Evidence</option>
                                <option value="supplementary">Supplementary</option>
                                <option value="historical">Historical</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">File *</label>
                            <input type="file" id="file" name="file" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <p class="mt-1 text-sm text-gray-600">Maximum file size: 10MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-0">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <button type="submit" class="w-full h-12 px-4 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all">Upload Document</button>
                        <a href="{{ route('admin.assessment-documents.index') }}" class="block w-full h-12 px-4 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 text-center leading-[3rem] transition-all">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Load units when assessment is selected
        document.getElementById('assessment_id').addEventListener('change', function() {
            const assessmentId = this.value;
            const unitSelect = document.getElementById('assessment_unit_id');
            const criteriaSelect = document.getElementById('assessment_criteria_id');

            // Reset dropdowns
            unitSelect.innerHTML = '<option value="">Select Unit</option>';
            criteriaSelect.innerHTML = '<option value="">Select Criteria</option>';

            if (!assessmentId) return;

            // Fetch units
            fetch(`{{ route('admin.assessment-documents.get-units') }}?assessment_id=${assessmentId}`)
                .then(res => res.json())
                .then(units => {
                    units.forEach(unit => {
                        unitSelect.innerHTML += `<option value="${unit.id}">${unit.unit_code} - ${unit.unit_title}</option>`;
                    });
                });
        });

        // Load criteria when unit is selected
        document.getElementById('assessment_unit_id').addEventListener('change', function() {
            const unitId = this.value;
            const criteriaSelect = document.getElementById('assessment_criteria_id');

            criteriaSelect.innerHTML = '<option value="">Select Criteria</option>';

            if (!unitId) return;

            fetch(`{{ route('admin.assessment-documents.get-criteria') }}?assessment_unit_id=${unitId}`)
                .then(res => res.json())
                .then(criteria => {
                    criteria.forEach(criterion => {
                        criteriaSelect.innerHTML += `<option value="${criterion.id}">${criterion.element_code} - ${criterion.element_title}</option>`;
                    });
                });
        });
    </script>
@endsection
