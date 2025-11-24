@extends('layouts.admin')

@section('title', 'Edit Revocation Request')

@php
    $active = 'certificate-revoke';
@endphp

@section('page_title', 'Edit Revocation Request')
@section('page_description', 'Update certificate revocation information')

@section('content')

<form action="{{ route('admin.certificate-revoke.update', $certificateRevoke) }}" method="POST" class="w-full">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Certificate Information (Read-only) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Information</h3>

                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <span class="material-symbols-outlined text-blue-600 text-2xl">workspace_premium</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">{{ $certificateRevoke->certificate->certificate_number }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $certificateRevoke->certificate->holder_name }}</p>
                            <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Scheme</p>
                                    <p class="font-medium text-gray-900">{{ $certificateRevoke->certificate->scheme->code }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Issue Date</p>
                                    <p class="font-medium text-gray-900">{{ $certificateRevoke->certificate->issue_date->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Status</p>
                                    <p class="font-medium text-gray-900">{{ ucfirst($certificateRevoke->certificate->status) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revocation Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Revocation Details</h3>

                <div class="space-y-4">
                    <!-- Revocation Reason Category -->
                    <div>
                        <label for="revocation_reason_category" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason Category <span class="text-red-500">*</span>
                        </label>
                        <select name="revocation_reason_category" id="revocation_reason_category" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('revocation_reason_category') border-red-500 @enderror">
                            <option value="holder_request" {{ old('revocation_reason_category', $certificateRevoke->revocation_reason_category) === 'holder_request' ? 'selected' : '' }}>Holder Request</option>
                            <option value="certification_withdrawn" {{ old('revocation_reason_category', $certificateRevoke->revocation_reason_category) === 'certification_withdrawn' ? 'selected' : '' }}>Certification Withdrawn</option>
                            <option value="fraud_misconduct" {{ old('revocation_reason_category', $certificateRevoke->revocation_reason_category) === 'fraud_misconduct' ? 'selected' : '' }}>Fraud/Misconduct</option>
                            <option value="competency_loss" {{ old('revocation_reason_category', $certificateRevoke->revocation_reason_category) === 'competency_loss' ? 'selected' : '' }}>Competency Loss</option>
                            <option value="non_compliance" {{ old('revocation_reason_category', $certificateRevoke->revocation_reason_category) === 'non_compliance' ? 'selected' : '' }}>Non-Compliance</option>
                            <option value="superseded" {{ old('revocation_reason_category', $certificateRevoke->revocation_reason_category) === 'superseded' ? 'selected' : '' }}>Superseded</option>
                            <option value="administrative" {{ old('revocation_reason_category', $certificateRevoke->revocation_reason_category) === 'administrative' ? 'selected' : '' }}>Administrative</option>
                            <option value="other" {{ old('revocation_reason_category', $certificateRevoke->revocation_reason_category) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('revocation_reason_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Revocation Reason -->
                    <div>
                        <label for="revocation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Detailed Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="revocation_reason" id="revocation_reason" rows="4" required placeholder="Provide detailed reason for revocation..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('revocation_reason') border-red-500 @enderror">{{ old('revocation_reason', $certificateRevoke->revocation_reason) }}</textarea>
                        @error('revocation_reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Revocation Date -->
                        <div>
                            <label for="revocation_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Revocation Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="revocation_date" id="revocation_date" value="{{ old('revocation_date', $certificateRevoke->revocation_date->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('revocation_date') border-red-500 @enderror">
                            @error('revocation_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Appealable -->
                        <div>
                            <label for="is_appealable" class="block text-sm font-medium text-gray-700 mb-2">
                                Appealable <span class="text-red-500">*</span>
                            </label>
                            <select name="is_appealable" id="is_appealable" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('is_appealable') border-red-500 @enderror">
                                <option value="1" {{ old('is_appealable', $certificateRevoke->is_appealable ? '1' : '0') === '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('is_appealable', $certificateRevoke->is_appealable ? '1' : '0') === '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('is_appealable')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Appeal Deadline -->
                        <div>
                            <label for="appeal_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                                Appeal Deadline
                            </label>
                            <input type="date" name="appeal_deadline" id="appeal_deadline" value="{{ old('appeal_deadline', $certificateRevoke->appeal_deadline?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('appeal_deadline') border-red-500 @enderror">
                            @error('appeal_deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Must be after revocation date</p>
                        </div>

                        <!-- Public Notification Required -->
                        <div>
                            <label for="public_notification_required" class="block text-sm font-medium text-gray-700 mb-2">
                                Public Notification Required <span class="text-red-500">*</span>
                            </label>
                            <select name="public_notification_required" id="public_notification_required" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('public_notification_required') border-red-500 @enderror">
                                <option value="0" {{ old('public_notification_required', $certificateRevoke->public_notification_required ? '1' : '0') === '0' ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('public_notification_required', $certificateRevoke->public_notification_required ? '1' : '0') === '1' ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('public_notification_required')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Information</h3>

                <div class="space-y-4">
                    <div>
                        <label for="impact_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Impact Notes
                        </label>
                        <textarea name="impact_notes" id="impact_notes" rows="3" placeholder="Describe the impact of this revocation..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('impact_notes') border-red-500 @enderror">{{ old('impact_notes', $certificateRevoke->impact_notes) }}</textarea>
                        @error('impact_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Describe the impact of this revocation</p>
                    </div>

                    <div>
                        <label for="internal_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Internal Notes
                        </label>
                        <textarea name="internal_notes" id="internal_notes" rows="3" placeholder="Add any internal notes..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('internal_notes') border-red-500 @enderror">{{ old('internal_notes', $certificateRevoke->internal_notes) }}</textarea>
                        @error('internal_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">For internal use only</p>
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
                        <span>Update Revocation</span>
                    </button>

                    <a href="{{ route('admin.certificate-revoke.show', $certificateRevoke) }}" class="w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold flex items-center justify-center gap-2">
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
                            <span class="material-symbols-outlined text-sm text-red-600">block</span>
                            <span>Cannot edit approved revocations</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Revocation Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Revocation Info</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Revocation Number</p>
                        <p class="font-medium text-gray-900">{{ $certificateRevoke->revocation_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Certificate Holder</p>
                        <p class="font-medium text-gray-900">{{ $certificateRevoke->certificate->holder_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        @php
                            $statusConfig = [
                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                'approved' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Approved'],
                                'rejected' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Rejected'],
                                'appealed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Appealed'],
                            ];
                            $config = $statusConfig[$certificateRevoke->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $certificateRevoke->status];
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
