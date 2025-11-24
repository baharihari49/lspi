@extends('layouts.admin')

@section('title', 'Create Renewal Request')

@php
    $active = 'certificate-renewal';
@endphp

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.certificate-renewal.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Renewal Request</h1>
            <p class="text-gray-600 mt-1">Request renewal for an existing certificate</p>
        </div>
    </div>

    <form action="{{ route('admin.certificate-renewal.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Certificate Selection -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Certificate Information</h2>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" name="original_certificate_id" value="{{ $certificate->id }}">

                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                        <div class="flex-1">
                            <p class="font-medium text-blue-900">{{ $certificate->certificate_number }}</p>
                            <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-blue-700">Holder: <strong>{{ $certificate->holder_name }}</strong></p>
                                    <p class="text-blue-700">Scheme: <strong>{{ $certificate->scheme->code }}</strong></p>
                                </div>
                                <div>
                                    <p class="text-blue-700">Issued: <strong>{{ $certificate->issue_date->format('d M Y') }}</strong></p>
                                    <p class="text-blue-700">Expires: <strong>{{ $certificate->valid_until->format('d M Y') }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Renewal Details -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Renewal Details</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Renewal Type -->
                    <div class="md:col-span-2">
                        <label for="renewal_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Renewal Type <span class="text-red-500">*</span>
                        </label>
                        <select name="renewal_type" id="renewal_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('renewal_type') border-red-500 @enderror">
                            <option value="">Select renewal type</option>
                            <option value="standard" {{ old('renewal_type') === 'standard' ? 'selected' : '' }}>Standard Renewal</option>
                            <option value="simplified" {{ old('renewal_type') === 'simplified' ? 'selected' : '' }}>Simplified Renewal</option>
                            <option value="automatic" {{ old('renewal_type') === 'automatic' ? 'selected' : '' }}>Automatic Renewal</option>
                            <option value="early_renewal" {{ old('renewal_type') === 'early_renewal' ? 'selected' : '' }}>Early Renewal</option>
                            <option value="late_renewal" {{ old('renewal_type') === 'late_renewal' ? 'selected' : '' }}>Late Renewal</option>
                            <option value="grace_period_renewal" {{ old('renewal_type') === 'grace_period_renewal' ? 'selected' : '' }}>Grace Period Renewal</option>
                        </select>
                        @error('renewal_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Renewal Due Date -->
                    <div>
                        <label for="renewal_due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Renewal Due Date
                        </label>
                        <input type="date" name="renewal_due_date" id="renewal_due_date" value="{{ old('renewal_due_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('renewal_due_date') border-red-500 @enderror">
                        @error('renewal_due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Requires Reassessment -->
                    <div>
                        <label for="requires_reassessment" class="block text-sm font-medium text-gray-700 mb-2">
                            Requires Reassessment <span class="text-red-500">*</span>
                        </label>
                        <select name="requires_reassessment" id="requires_reassessment" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('requires_reassessment') border-red-500 @enderror">
                            <option value="0" {{ old('requires_reassessment', '0') === '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('requires_reassessment') === '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('requires_reassessment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- CPD Requirements -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">CPD (Continuing Professional Development)</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- CPD Required -->
                    <div>
                        <label for="cpd_required" class="block text-sm font-medium text-gray-700 mb-2">
                            CPD Required <span class="text-red-500">*</span>
                        </label>
                        <select name="cpd_required" id="cpd_required" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cpd_required') border-red-500 @enderror">
                            <option value="0" {{ old('cpd_required', '0') === '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('cpd_required') === '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('cpd_required')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CPD Hours Required -->
                    <div>
                        <label for="cpd_hours_required" class="block text-sm font-medium text-gray-700 mb-2">
                            CPD Hours Required
                        </label>
                        <input type="number" name="cpd_hours_required" id="cpd_hours_required" value="{{ old('cpd_hours_required', 0) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cpd_hours_required') border-red-500 @enderror">
                        @error('cpd_hours_required')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Information -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Fee Information</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label for="renewal_fee" class="block text-sm font-medium text-gray-700 mb-2">
                        Renewal Fee (Rp)
                    </label>
                    <input type="number" name="renewal_fee" id="renewal_fee" value="{{ old('renewal_fee', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('renewal_fee') border-red-500 @enderror">
                    @error('renewal_fee')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Leave as 0 if no fee required</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.certificate-renewal.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                Create Renewal Request
            </button>
        </div>
    </form>
</div>
@endsection
