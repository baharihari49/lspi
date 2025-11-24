@extends('layouts.admin')

@section('title', 'Issue New Certificate')

@php
    $active = 'certificates';
@endphp

@section('page_title', 'Issue New Certificate')
@section('page_description', 'Create a new certificate for an approved assessment result')

@section('content')
<form action="{{ route('admin.certificates.store') }}" method="POST" class="w-full">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assessment Result Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Assessment Result</h3>

                @if($assessmentResultId)
                    <!-- Pre-selected assessment result -->
                    <input type="hidden" name="assessment_result_id" value="{{ $assessmentResult->id }}">

                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-blue-700">Assessee</p>
                                <p class="font-semibold text-blue-900">{{ $assessmentResult->assessee->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-blue-700">Scheme</p>
                                <p class="font-semibold text-blue-900">{{ $assessmentResult->scheme->code }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-blue-700">Result</p>
                                <p class="font-semibold text-green-700">{{ ucfirst($assessmentResult->final_result) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-blue-700">Approval Date</p>
                                <p class="font-semibold text-blue-900">{{ $assessmentResult->approved_at ? $assessmentResult->approved_at->format('d M Y') : $assessmentResult->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Dropdown to select assessment result -->
                    <div>
                        <label for="assessment_result_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Assessment Result <span class="text-red-500">*</span>
                        </label>
                        <select name="assessment_result_id" id="assessment_result_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('assessment_result_id') border-red-500 @enderror">
                            <option value="">-- Select an approved assessment result --</option>
                            @foreach($assessmentResults as $result)
                                <option value="{{ $result->id }}" {{ old('assessment_result_id') == $result->id ? 'selected' : '' }}>
                                    {{ $result->assessee->name }} - {{ $result->scheme->code }} ({{ $result->approved_at ? $result->approved_at->format('d M Y') : $result->created_at->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('assessment_result_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Only approved competent assessment results without certificates are shown</p>
                    </div>
                @endif
            </div>

            <!-- Certificate Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Details</h3>

                <div class="space-y-4">
                    <!-- Certificate Name -->
                    <div>
                        <label for="certificate_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Certificate Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="certificate_name" id="certificate_name" value="{{ old('certificate_name', $assessmentResult?->scheme?->name ?? '') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('certificate_name') border-red-500 @enderror">
                        @error('certificate_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Issue Date -->
                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Issue Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('issue_date') border-red-500 @enderror">
                            @error('issue_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Valid From -->
                        <div>
                            <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-2">
                                Valid From <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valid_from') border-red-500 @enderror">
                            @error('valid_from')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Validity Period -->
                        <div>
                            <label for="validity_period_months" class="block text-sm font-medium text-gray-700 mb-2">
                                Validity Period (Months) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="validity_period_months" id="validity_period_months" value="{{ old('validity_period_months', 36) }}" min="1" max="60" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('validity_period_months') border-red-500 @enderror">
                            @error('validity_period_months')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Standard: 36 months (3 years)</p>
                        </div>

                        <!-- Language -->
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-2">
                                Certificate Language <span class="text-red-500">*</span>
                            </label>
                            <select name="language" id="language" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('language') border-red-500 @enderror">
                                <option value="id" {{ old('language', 'id') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            @error('language')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Template Name -->
                    <div>
                        <label for="template_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Template Name
                        </label>
                        <input type="text" name="template_name" id="template_name" value="{{ old('template_name', 'default') }}" placeholder="default" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('template_name') border-red-500 @enderror">
                        @error('template_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Leave as 'default' for standard template</p>
                    </div>

                    <!-- Special Conditions -->
                    <div>
                        <label for="special_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                            Special Conditions
                        </label>
                        <textarea name="special_conditions" id="special_conditions" rows="3" placeholder="Any special conditions or restrictions..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('special_conditions') border-red-500 @enderror">{{ old('special_conditions') }}</textarea>
                        @error('special_conditions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Will be printed on certificate if specified</p>
                    </div>
                </div>
            </div>

            <!-- Holder Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Holder</h3>

                <div class="space-y-4">
                    <!-- Full Name -->
                    <div>
                        <label for="holder_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="holder_name" id="holder_name" value="{{ old('holder_name', $assessmentResult?->assessee?->name ?? '') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('holder_name') border-red-500 @enderror">
                        @error('holder_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="holder_id_number" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Number (NIK/KTP)
                        </label>
                        <input type="text" name="holder_id_number" id="holder_id_number" value="{{ old('holder_id_number', $assessmentResult?->assessee?->nik ?? '') }}" maxlength="16" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('holder_id_number') border-red-500 @enderror">
                        @error('holder_id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Optional: National ID number</p>
                    </div>
                </div>
            </div>

            <!-- Unit Codes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Competency Unit Codes</h3>

                <div id="unit-codes-container" class="space-y-3">
                    <div class="unit-code-row flex items-center gap-2">
                        <input type="text" name="unit_codes[]" placeholder="e.g., J.62SFT00.001.2" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" onclick="removeUnitCode(this)" class="p-2.5 text-red-600 hover:bg-red-50 rounded-lg transition">
                            <span class="material-symbols-outlined">remove_circle</span>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addUnitCode()" class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition font-medium">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    <span>Add Another Unit Code</span>
                </button>
            </div>

            <!-- Signatories -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Signatories</h3>

                <div id="signatories-container" class="space-y-4">
                    <div class="signatory-row p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" name="signatories[0][name]" placeholder="Signatory name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                <input type="text" name="signatories[0][position]" placeholder="e.g., Director" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIP/ID</label>
                                <input type="text" name="signatories[0][nip]" placeholder="Employee ID" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <button type="button" onclick="removeSignatory(this)" class="mt-3 inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-700 font-medium">
                            <span class="material-symbols-outlined text-base">remove_circle</span>
                            <span>Remove</span>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addSignatory()" class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition font-medium">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    <span>Add Another Signatory</span>
                </button>
            </div>
        </div>

        <!-- Right Column: Summary & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                <div class="space-y-3">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-semibold flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">workspace_premium</span>
                        <span>Issue Certificate</span>
                    </button>

                    <a href="{{ route('admin.certificates.index') }}" class="w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">close</span>
                        <span>Cancel</span>
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Information</h4>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">info</span>
                            <span>Certificate number will be auto-generated</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">qr_code</span>
                            <span>QR code for verification will be created automatically</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">history</span>
                            <span>All certificate activities will be logged</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Internal Notes</h3>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="notes" id="notes" rows="4" placeholder="Add any internal notes..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">For internal use only, not printed on certificate</p>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let unitCodeIndex = 1;
let signatoryIndex = 1;

function addUnitCode() {
    const container = document.getElementById('unit-codes-container');
    const row = document.createElement('div');
    row.className = 'unit-code-row flex items-center gap-2';
    row.innerHTML = `
        <input type="text" name="unit_codes[]" placeholder="e.g., J.62SFT00.001.2" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="button" onclick="removeUnitCode(this)" class="p-2.5 text-red-600 hover:bg-red-50 rounded-lg transition">
            <span class="material-symbols-outlined">remove_circle</span>
        </button>
    `;
    container.appendChild(row);
    unitCodeIndex++;
}

function removeUnitCode(button) {
    const container = document.getElementById('unit-codes-container');
    if (container.children.length > 1) {
        button.closest('.unit-code-row').remove();
    } else {
        alert('At least one unit code row must remain');
    }
}

function addSignatory() {
    const container = document.getElementById('signatories-container');
    const row = document.createElement('div');
    row.className = 'signatory-row p-4 border border-gray-200 rounded-lg bg-gray-50';
    row.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="signatories[${signatoryIndex}][name]" placeholder="Signatory name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                <input type="text" name="signatories[${signatoryIndex}][position]" placeholder="e.g., Director" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIP/ID</label>
                <input type="text" name="signatories[${signatoryIndex}][nip]" placeholder="Employee ID" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <button type="button" onclick="removeSignatory(this)" class="mt-3 inline-flex items-center gap-1 text-sm text-red-600 hover:text-red-700 font-medium">
            <span class="material-symbols-outlined text-base">remove_circle</span>
            <span>Remove</span>
        </button>
    `;
    container.appendChild(row);
    signatoryIndex++;
}

function removeSignatory(button) {
    button.closest('.signatory-row').remove();
}
</script>
@endpush
@endsection
