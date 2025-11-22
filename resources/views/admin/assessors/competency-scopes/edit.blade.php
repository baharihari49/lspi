@extends('layouts.admin')

@section('title', 'Edit Competency Scope')

@php
    $active = 'assessor-competency-scopes';
@endphp

@section('page_title', 'Edit Competency Scope')
@section('page_description', 'Update assessor competency certification information')

@section('content')
    <form action="{{ route('admin.assessor-competency-scopes.update', $assessorCompetencyScope) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Assessor Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assessor Information</h3>

                    <div>
                        <label for="assessor_id" class="block text-sm font-semibold text-gray-700 mb-2">Assessor *</label>
                        <select id="assessor_id" name="assessor_id" required
                            class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('assessor_id') border-red-500 @enderror">
                            <option value="">Select Assessor</option>
                            @foreach($assessors as $assessor)
                                <option value="{{ $assessor->id }}" {{ old('assessor_id', $assessorCompetencyScope->assessor_id) == $assessor->id ? 'selected' : '' }}>
                                    {{ $assessor->full_name }} ({{ $assessor->registration_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('assessor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Scheme Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Certification Scheme</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Scheme Code -->
                        <div>
                            <label for="scheme_code" class="block text-sm font-semibold text-gray-700 mb-2">Scheme Code *</label>
                            <input type="text" id="scheme_code" name="scheme_code" value="{{ old('scheme_code', $assessorCompetencyScope->scheme_code) }}" required maxlength="50"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('scheme_code') border-red-500 @enderror"
                                placeholder="e.g., SKM-001">
                            @error('scheme_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheme Name -->
                        <div>
                            <label for="scheme_name" class="block text-sm font-semibold text-gray-700 mb-2">Scheme Name *</label>
                            <input type="text" id="scheme_name" name="scheme_name" value="{{ old('scheme_name', $assessorCompetencyScope->scheme_name) }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('scheme_name') border-red-500 @enderror"
                                placeholder="e.g., Pengelolaan Jurnal Elektronik">
                            @error('scheme_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Competency Unit -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Competency Unit</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Competency Unit Code -->
                        <div>
                            <label for="competency_unit_code" class="block text-sm font-semibold text-gray-700 mb-2">Unit Code *</label>
                            <input type="text" id="competency_unit_code" name="competency_unit_code" value="{{ old('competency_unit_code', $assessorCompetencyScope->competency_unit_code) }}" required maxlength="50"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('competency_unit_code') border-red-500 @enderror"
                                placeholder="e.g., J.62PIE01.001.1">
                            @error('competency_unit_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Competency Unit Title -->
                        <div>
                            <label for="competency_unit_title" class="block text-sm font-semibold text-gray-700 mb-2">Unit Title *</label>
                            <input type="text" id="competency_unit_title" name="competency_unit_title" value="{{ old('competency_unit_title', $assessorCompetencyScope->competency_unit_title) }}" required maxlength="255"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('competency_unit_title') border-red-500 @enderror"
                                placeholder="e.g., Mengelola Sistem OJS">
                            @error('competency_unit_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Certificate Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Certificate Number -->
                        <div class="md:col-span-2">
                            <label for="certificate_number" class="block text-sm font-semibold text-gray-700 mb-2">Certificate Number</label>
                            <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number', $assessorCompetencyScope->certificate_number) }}" maxlength="100"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono text-sm @error('certificate_number') border-red-500 @enderror"
                                placeholder="e.g., CERT-ASR-2025-001">
                            @error('certificate_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certificate Issued Date -->
                        <div>
                            <label for="certificate_issued_date" class="block text-sm font-semibold text-gray-700 mb-2">Issued Date</label>
                            <input type="date" id="certificate_issued_date" name="certificate_issued_date" value="{{ old('certificate_issued_date', $assessorCompetencyScope->certificate_issued_date?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certificate_issued_date') border-red-500 @enderror">
                            @error('certificate_issued_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certificate Expiry Date -->
                        <div>
                            <label for="certificate_expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" id="certificate_expiry_date" name="certificate_expiry_date" value="{{ old('certificate_expiry_date', $assessorCompetencyScope->certificate_expiry_date?->format('Y-m-d')) }}"
                                class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('certificate_expiry_date') border-red-500 @enderror">
                            @error('certificate_expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank if the certificate does not expire</p>
                        </div>

                        <!-- Is Active -->
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $assessorCompetencyScope->is_active) ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-900 border-gray-300 rounded focus:ring-blue-500">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">Active Competency Scope</p>
                                    <p class="text-xs text-gray-600">Assessor can perform assessments under this competency scope</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Info -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-0 space-y-6">
                    <!-- Status Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Current Status</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Approval Status</p>
                                @php
                                    $approvalColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'expired' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $approvalColors[$assessorCompetencyScope->approval_status] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($assessorCompetencyScope->approval_status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-3">Metadata</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-semibold text-gray-900">{{ $assessorCompetencyScope->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated:</span>
                                <span class="font-semibold text-gray-900">{{ $assessorCompetencyScope->updated_at->format('d M Y') }}</span>
                            </div>
                            @if($assessorCompetencyScope->approved_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Approved:</span>
                                    <span class="font-semibold text-gray-900">{{ $assessorCompetencyScope->approved_at->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">save</span>
                                <span>Update Competency Scope</span>
                            </button>
                            <a href="{{ route('admin.assessor-competency-scopes.show', $assessorCompetencyScope) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                                <span class="material-symbols-outlined">cancel</span>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
