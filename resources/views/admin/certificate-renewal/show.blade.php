@extends('layouts.admin')

@section('title', 'Renewal Details')

@php
    $active = 'certificate-renewal';
@endphp

@section('page_title', $certificateRenewal->renewal_number)
@section('page_description', 'Certificate renewal request details')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-red-600 mr-3">error</span>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Renewal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Renewal Information</h3>
                    @if(in_array($certificateRenewal->status, ['pending', 'in_assessment']))
                        <a href="{{ route('admin.certificate-renewal.edit', $certificateRenewal) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit</span>
                        </a>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Renewal Number</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $certificateRenewal->renewal_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                        'in_assessment' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'In Assessment'],
                                        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Approved'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Rejected'],
                                        'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Cancelled'],
                                    ];
                                    $config = $statusConfig[$certificateRenewal->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $certificateRenewal->status];
                                @endphp
                                <span class="px-3 py-1 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-xs font-semibold">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Renewal Type</label>
                            <p class="mt-1 text-gray-900">{{ str_replace('_', ' ', ucfirst($certificateRenewal->renewal_type)) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Request Date</label>
                            <p class="mt-1 text-gray-900">{{ $certificateRenewal->renewal_request_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Original Expiry Date</label>
                            <p class="mt-1 text-gray-900">{{ $certificateRenewal->original_expiry_date->format('d F Y') }}</p>
                        </div>
                        @if($certificateRenewal->new_expiry_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">New Expiry Date</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $certificateRenewal->new_expiry_date->format('d F Y') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($certificateRenewal->requires_reassessment)
                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500">Reassessment Required</label>
                            <p class="mt-1 text-yellow-700 font-medium">Yes - Full reassessment is required for this renewal</p>
                        </div>
                    @endif

                    @if($certificateRenewal->cpd_required)
                        <div class="pt-4 border-t border-gray-200 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">CPD Hours Required</label>
                                <p class="mt-1 text-gray-900">{{ $certificateRenewal->cpd_hours_required ?? 0 }} hours</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">CPD Hours Completed</label>
                                <p class="mt-1 text-gray-900">{{ $certificateRenewal->cpd_hours_completed ?? 0 }} hours</p>
                            </div>
                        </div>
                    @endif

                    @if($certificateRenewal->renewal_fee)
                        <div class="pt-4 border-t border-gray-200 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Renewal Fee</label>
                                <p class="mt-1 text-gray-900">Rp {{ number_format($certificateRenewal->renewal_fee, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Payment Status</label>
                                @if($certificateRenewal->fee_paid)
                                    <p class="mt-1 text-green-700 font-medium">Paid on {{ $certificateRenewal->fee_paid_date->format('d M Y') }}</p>
                                @else
                                    <p class="mt-1 text-red-700 font-medium">Not Paid</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Original Certificate -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Original Certificate</h3>

                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <span class="material-symbols-outlined text-blue-600 text-3xl">workspace_premium</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ $certificateRenewal->originalCertificate->certificate_number }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $certificateRenewal->originalCertificate->holder_name }}</p>
                        <div class="mt-3 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Holder</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $certificateRenewal->originalCertificate->holder_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Scheme</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $certificateRenewal->originalCertificate->scheme->code }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.certificates.show', $certificateRenewal->originalCertificate) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium mt-3">
                            <span>View Certificate</span>
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($certificateRenewal->notes_for_assessee || $certificateRenewal->internal_notes || $certificateRenewal->decision_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Notes</h3>

                    <div class="space-y-4">
                        @if($certificateRenewal->notes_for_assessee)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Notes for Assessee</label>
                                <p class="mt-1 text-gray-900">{{ $certificateRenewal->notes_for_assessee }}</p>
                            </div>
                        @endif
                        @if($certificateRenewal->internal_notes)
                            <div class="pt-4 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-500">Internal Notes</label>
                                <p class="mt-1 text-gray-900">{{ $certificateRenewal->internal_notes }}</p>
                            </div>
                        @endif
                        @if($certificateRenewal->decision_notes)
                            <div class="pt-4 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-500">Decision Notes</label>
                                <p class="mt-1 text-gray-900">{{ $certificateRenewal->decision_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            @if($certificateRenewal->status === 'pending')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <!-- Approve Form -->
                    <form action="{{ route('admin.certificate-renewal.approve', $certificateRenewal) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="extension_months" class="block text-sm font-medium text-gray-700 mb-2">Extension (Months) <span class="text-red-500">*</span></label>
                            <input type="number" name="extension_months" id="extension_months" value="36" min="1" max="60" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="decision_notes" class="block text-sm font-medium text-gray-700 mb-2">Decision Notes</label>
                            <textarea name="decision_notes" id="decision_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span class="font-medium">Approve Renewal</span>
                        </button>
                    </form>

                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- Reject Form -->
                    <form action="{{ route('admin.certificate-renewal.reject', $certificateRenewal) }}" method="POST" class="space-y-3" onsubmit="return confirm('Are you sure you want to reject this renewal?')">
                        @csrf
                        <div>
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span class="font-medium">Reject Renewal</span>
                        </button>
                    </form>
                </div>
            @endif

            <!-- Process Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Process Info</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Requested By</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $certificateRenewal->requestedBy->name ?? 'System' }}</p>
                    </div>
                    @if($certificateRenewal->processedBy)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Processed By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificateRenewal->processedBy->name }}</p>
                            <p class="text-xs text-gray-500">{{ $certificateRenewal->renewal_processed_date->format('d M Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
