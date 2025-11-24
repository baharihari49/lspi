@extends('layouts.admin')

@section('title', 'Edit Renewal Request')

@php
    $active = 'certificate-renewal';
@endphp

@section('page_title', 'Edit Renewal Request')
@section('page_description', 'Update certificate renewal information')

@section('content')

<form action="{{ route('admin.certificate-renewal.update', $certificateRenewal) }}" method="POST" class="w-full">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Certificate Information (Read-only) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Original Certificate</h3>

                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <span class="material-symbols-outlined text-blue-600 text-2xl">workspace_premium</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">{{ $certificateRenewal->originalCertificate->certificate_number }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $certificateRenewal->originalCertificate->holder_name }}</p>
                            <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Scheme</p>
                                    <p class="font-medium text-gray-900">{{ $certificateRenewal->originalCertificate->scheme->code }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Issue Date</p>
                                    <p class="font-medium text-gray-900">{{ $certificateRenewal->originalCertificate->issue_date->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Expiry Date</p>
                                    <p class="font-medium text-gray-900">{{ $certificateRenewal->originalCertificate->valid_until->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Renewal Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Renewal Details</h3>

                <div class="space-y-4">
                    <!-- Renewal Type -->
                    <div>
                        <label for="renewal_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Renewal Type <span class="text-red-500">*</span>
                        </label>
                        <select name="renewal_type" id="renewal_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('renewal_type') border-red-500 @enderror">
                            <option value="standard" {{ old('renewal_type', $certificateRenewal->renewal_type) === 'standard' ? 'selected' : '' }}>Standard Renewal</option>
                            <option value="simplified" {{ old('renewal_type', $certificateRenewal->renewal_type) === 'simplified' ? 'selected' : '' }}>Simplified Renewal</option>
                            <option value="automatic" {{ old('renewal_type', $certificateRenewal->renewal_type) === 'automatic' ? 'selected' : '' }}>Automatic Renewal</option>
                            <option value="early_renewal" {{ old('renewal_type', $certificateRenewal->renewal_type) === 'early_renewal' ? 'selected' : '' }}>Early Renewal</option>
                            <option value="late_renewal" {{ old('renewal_type', $certificateRenewal->renewal_type) === 'late_renewal' ? 'selected' : '' }}>Late Renewal</option>
                            <option value="grace_period_renewal" {{ old('renewal_type', $certificateRenewal->renewal_type) === 'grace_period_renewal' ? 'selected' : '' }}>Grace Period Renewal</option>
                        </select>
                        @error('renewal_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Renewal Due Date -->
                        <div>
                            <label for="renewal_due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Renewal Due Date
                            </label>
                            <input type="date" name="renewal_due_date" id="renewal_due_date" value="{{ old('renewal_due_date', $certificateRenewal->renewal_due_date?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('renewal_due_date') border-red-500 @enderror">
                            @error('renewal_due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Requires Reassessment -->
                        <div>
                            <label for="requires_reassessment" class="block text-sm font-medium text-gray-700 mb-2">
                                Requires Reassessment <span class="text-red-500">*</span>
                            </label>
                            <select name="requires_reassessment" id="requires_reassessment" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('requires_reassessment') border-red-500 @enderror">
                                <option value="0" {{ old('requires_reassessment', $certificateRenewal->requires_reassessment ? '1' : '0') === '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('requires_reassessment', $certificateRenewal->requires_reassessment ? '1' : '0') === '1' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('requires_reassessment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- CPD Requirements -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">CPD (Continuing Professional Development)</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- CPD Required -->
                        <div>
                            <label for="cpd_required" class="block text-sm font-medium text-gray-700 mb-2">
                                CPD Required <span class="text-red-500">*</span>
                            </label>
                            <select name="cpd_required" id="cpd_required" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cpd_required') border-red-500 @enderror">
                                <option value="0" {{ old('cpd_required', $certificateRenewal->cpd_required ? '1' : '0') === '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('cpd_required', $certificateRenewal->cpd_required ? '1' : '0') === '1' ? 'selected' : '' }}>Yes</option>
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
                            <input type="number" name="cpd_hours_required" id="cpd_hours_required" value="{{ old('cpd_hours_required', $certificateRenewal->cpd_hours_required) }}" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cpd_hours_required') border-red-500 @enderror">
                            @error('cpd_hours_required')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CPD Hours Completed -->
                        <div>
                            <label for="cpd_hours_completed" class="block text-sm font-medium text-gray-700 mb-2">
                                CPD Hours Completed
                            </label>
                            <input type="number" name="cpd_hours_completed" id="cpd_hours_completed" value="{{ old('cpd_hours_completed', $certificateRenewal->cpd_hours_completed) }}" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cpd_hours_completed') border-red-500 @enderror">
                            @error('cpd_hours_completed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fee Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Fee Information</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="renewal_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                Renewal Fee (Rp)
                            </label>
                            <input type="number" name="renewal_fee" id="renewal_fee" value="{{ old('renewal_fee', $certificateRenewal->renewal_fee) }}" min="0" step="0.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('renewal_fee') border-red-500 @enderror">
                            @error('renewal_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fee_paid" class="block text-sm font-medium text-gray-700 mb-2">
                                Fee Paid <span class="text-red-500">*</span>
                            </label>
                            <select name="fee_paid" id="fee_paid" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fee_paid') border-red-500 @enderror">
                                <option value="0" {{ old('fee_paid', $certificateRenewal->fee_paid ? '1' : '0') === '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('fee_paid', $certificateRenewal->fee_paid ? '1' : '0') === '1' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('fee_paid')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fee_paid_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fee Paid Date
                            </label>
                            <input type="date" name="fee_paid_date" id="fee_paid_date" value="{{ old('fee_paid_date', $certificateRenewal->fee_paid_date?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fee_paid_date') border-red-500 @enderror">
                            @error('fee_paid_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Reference
                            </label>
                            <input type="text" name="payment_reference" id="payment_reference" value="{{ old('payment_reference', $certificateRenewal->payment_reference) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_reference') border-red-500 @enderror">
                            @error('payment_reference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Notes</h3>

                <div class="space-y-4">
                    <div>
                        <label for="internal_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Internal Notes
                        </label>
                        <textarea name="internal_notes" id="internal_notes" rows="3" placeholder="Add any internal notes..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('internal_notes') border-red-500 @enderror">{{ old('internal_notes', $certificateRenewal->internal_notes) }}</textarea>
                        @error('internal_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">For internal use only</p>
                    </div>

                    <div>
                        <label for="notes_for_assessee" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes for Assessee
                        </label>
                        <textarea name="notes_for_assessee" id="notes_for_assessee" rows="3" placeholder="Add any notes for the assessee..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes_for_assessee') border-red-500 @enderror">{{ old('notes_for_assessee', $certificateRenewal->notes_for_assessee) }}</textarea>
                        @error('notes_for_assessee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">This will be visible to the assessee</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                <div class="space-y-3">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-semibold flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        <span>Update Renewal</span>
                    </button>

                    <a href="{{ route('admin.certificate-renewal.show', $certificateRenewal) }}" class="w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">close</span>
                        <span>Cancel</span>
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Information</h4>
                    <ul class="space-y-2 text-xs text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">info</span>
                            <span>Only editable fields are shown</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">history</span>
                            <span>All changes will be logged</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-sm text-blue-600">lock</span>
                            <span>Cannot edit approved/rejected renewals</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Renewal Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Renewal Info</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Renewal Number</p>
                        <p class="font-medium text-gray-900">{{ $certificateRenewal->renewal_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Assessee</p>
                        <p class="font-medium text-gray-900">{{ $certificateRenewal->assessee->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                'in_assessment' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'In Assessment'],
                                'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Approved'],
                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Rejected'],
                            ];
                            $config = $statusConfig[$certificateRenewal->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $certificateRenewal->status];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                            {{ $config['label'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
