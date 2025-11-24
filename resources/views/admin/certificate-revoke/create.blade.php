@extends('layouts.admin')

@section('title', 'Create Revocation Request')

@php
    $active = 'certificate-revoke';
@endphp

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.certificate-revoke.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Revocation Request</h1>
            <p class="text-gray-600 mt-1">Request revocation for an existing certificate</p>
        </div>
    </div>

    <form action="{{ route('admin.certificate-revoke.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Certificate Selection -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Certificate Information</h2>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">

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
                                    <p class="text-blue-700">Status: <strong>{{ ucfirst($certificate->status) }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revocation Details -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Revocation Details</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Revocation Reason Category -->
                    <div class="md:col-span-2">
                        <label for="revocation_reason_category" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason Category <span class="text-red-500">*</span>
                        </label>
                        <select name="revocation_reason_category" id="revocation_reason_category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('revocation_reason_category') border-red-500 @enderror">
                            <option value="">Select a reason category</option>
                            <option value="holder_request" {{ old('revocation_reason_category') === 'holder_request' ? 'selected' : '' }}>Holder Request</option>
                            <option value="certification_withdrawn" {{ old('revocation_reason_category') === 'certification_withdrawn' ? 'selected' : '' }}>Certification Withdrawn</option>
                            <option value="fraud_misconduct" {{ old('revocation_reason_category') === 'fraud_misconduct' ? 'selected' : '' }}>Fraud/Misconduct</option>
                            <option value="competency_loss" {{ old('revocation_reason_category') === 'competency_loss' ? 'selected' : '' }}>Competency Loss</option>
                            <option value="non_compliance" {{ old('revocation_reason_category') === 'non_compliance' ? 'selected' : '' }}>Non-Compliance</option>
                            <option value="superseded" {{ old('revocation_reason_category') === 'superseded' ? 'selected' : '' }}>Superseded</option>
                            <option value="administrative" {{ old('revocation_reason_category') === 'administrative' ? 'selected' : '' }}>Administrative</option>
                            <option value="other" {{ old('revocation_reason_category') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('revocation_reason_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Revocation Reason -->
                    <div class="md:col-span-2">
                        <label for="revocation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Detailed Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="revocation_reason" id="revocation_reason" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('revocation_reason') border-red-500 @enderror">{{ old('revocation_reason') }}</textarea>
                        @error('revocation_reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Provide a detailed explanation for the revocation</p>
                    </div>

                    <!-- Revocation Date -->
                    <div>
                        <label for="revocation_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Revocation Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="revocation_date" id="revocation_date" value="{{ old('revocation_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('revocation_date') border-red-500 @enderror">
                        @error('revocation_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Appealable -->
                    <div>
                        <label for="is_appealable" class="block text-sm font-medium text-gray-700 mb-2">
                            Appealable <span class="text-red-500">*</span>
                        </label>
                        <select name="is_appealable" id="is_appealable" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('is_appealable') border-red-500 @enderror">
                            <option value="1" {{ old('is_appealable', '1') === '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('is_appealable') === '0' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('is_appealable')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Public Notification Required -->
                    <div>
                        <label for="public_notification_required" class="block text-sm font-medium text-gray-700 mb-2">
                            Public Notification Required <span class="text-red-500">*</span>
                        </label>
                        <select name="public_notification_required" id="public_notification_required" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('public_notification_required') border-red-500 @enderror">
                            <option value="0" {{ old('public_notification_required', '0') === '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('public_notification_required') === '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('public_notification_required')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.certificate-revoke.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                Create Revocation Request
            </button>
        </div>
    </form>
</div>
@endsection
