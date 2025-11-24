@extends('layouts.admin')

@section('title', 'Revocation Details')

@php
    $active = 'certificate-revoke';
@endphp

@section('page_title', $certificateRevoke->revocation_number)
@section('page_description', 'Certificate revocation request details')

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
            <!-- Revocation Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Revocation Information</h3>
                    @if($certificateRevoke->status === 'pending')
                        <a href="{{ route('admin.certificate-revoke.edit', $certificateRevoke) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            <span>Edit</span>
                        </a>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Revocation Number</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $certificateRevoke->revocation_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pending'],
                                        'approved' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Approved'],
                                        'rejected' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Rejected'],
                                        'appealed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Appealed'],
                                        'withdrawn' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Withdrawn'],
                                    ];
                                    $config = $statusConfig[$certificateRevoke->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $certificateRevoke->status];
                                @endphp
                                <span class="px-3 py-1 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-xs font-semibold">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Reason Category</label>
                            <p class="mt-1 text-gray-900">{{ str_replace('_', ' ', ucfirst($certificateRevoke->revocation_reason_category)) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Revocation Date</label>
                            <p class="mt-1 text-gray-900">{{ $certificateRevoke->revocation_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Request Date</label>
                            <p class="mt-1 text-gray-900">{{ $certificateRevoke->revocation_request_date->format('d F Y') }}</p>
                        </div>
                        @if($certificateRevoke->revocation_approval_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Approval Date</label>
                                <p class="mt-1 text-gray-900">{{ $certificateRevoke->revocation_approval_date->format('d F Y') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Revocation Reason</label>
                        <p class="text-gray-900">{{ $certificateRevoke->revocation_reason }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-200 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Appealable</label>
                            @if($certificateRevoke->is_appealable)
                                <p class="mt-1 text-green-700 font-medium">Yes</p>
                                @if($certificateRevoke->appeal_deadline)
                                    <p class="text-sm text-gray-600">Deadline: {{ $certificateRevoke->appeal_deadline->format('d M Y') }}</p>
                                @endif
                            @else
                                <p class="mt-1 text-red-700 font-medium">No</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Public Notification</label>
                            <p class="mt-1 {{ $certificateRevoke->public_notification_required ? 'text-yellow-700' : 'text-gray-700' }} font-medium">
                                {{ $certificateRevoke->public_notification_required ? 'Required' : 'Not Required' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certificate Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Information</h3>

                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <span class="material-symbols-outlined text-blue-600 text-3xl">workspace_premium</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ $certificateRevoke->certificate->certificate_number }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $certificateRevoke->certificate->holder_name }}</p>
                        <div class="mt-3 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Holder</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $certificateRevoke->certificate->holder_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Scheme</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $certificateRevoke->certificate->scheme->code }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Issue Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $certificateRevoke->certificate->issue_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Valid Until</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $certificateRevoke->certificate->valid_until->format('d M Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.certificates.show', $certificateRevoke->certificate) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium mt-3">
                            <span>View Certificate</span>
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($certificateRevoke->impact_notes || $certificateRevoke->internal_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Notes</h3>

                    <div class="space-y-4">
                        @if($certificateRevoke->impact_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Impact Notes</label>
                                <p class="mt-1 text-gray-900">{{ $certificateRevoke->impact_notes }}</p>
                            </div>
                        @endif
                        @if($certificateRevoke->internal_notes)
                            <div class="pt-4 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-500">Internal Notes</label>
                                <p class="mt-1 text-gray-900">{{ $certificateRevoke->internal_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            @if($certificateRevoke->status === 'pending')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>

                    <!-- Approve Form -->
                    <form action="{{ route('admin.certificate-revoke.approve', $certificateRevoke) }}" method="POST" class="mb-4" onsubmit="return confirm('Are you sure you want to approve this revocation? The certificate will be permanently revoked.')">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span class="font-medium">Approve Revocation</span>
                        </button>
                    </form>

                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- Reject Form -->
                    <form action="{{ route('admin.certificate-revoke.reject', $certificateRevoke) }}" method="POST" class="space-y-3" onsubmit="return confirm('Are you sure you want to reject this revocation?')">
                        @csrf
                        <div>
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <span class="material-symbols-outlined">cancel</span>
                            <span class="font-medium">Reject Revocation</span>
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
                        <p class="mt-1 text-sm text-gray-900">{{ $certificateRevoke->revokedBy->name ?? 'System' }}</p>
                    </div>
                    @if($certificateRevoke->approvedBy)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Approved By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificateRevoke->approvedBy->name }}</p>
                            <p class="text-xs text-gray-500">{{ $certificateRevoke->revocation_approval_date->format('d M Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
