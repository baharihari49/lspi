@extends('layouts.admin')

@section('title', 'Certificate Details')

@php
    $active = 'certificates';
@endphp

@section('page_title', $certificate->certificate_number)
@section('page_description', 'Certificate details and validation information')

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

    @if(session('info'))
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-blue-600 mr-3">info</span>
            <p class="text-blue-800 font-medium">{{ session('info') }}</p>
        </div>
    @endif

    <!-- Status Banner -->
    @if($certificate->isExpiringSoon() && $certificate->status === 'active')
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-yellow-600 mt-0.5">warning</span>
                <div class="flex-1">
                    <p class="font-medium text-yellow-900">Certificate Expiring Soon</p>
                    <p class="text-sm text-yellow-700 mt-1">This certificate will expire in {{ $certificate->daysUntilExpiry() }} days ({{ $certificate->valid_until->format('d M Y') }})</p>
                    <a href="{{ route('admin.certificate-renewal.create', ['certificate_id' => $certificate->id]) }}" class="inline-flex items-center gap-1 text-sm font-medium text-yellow-800 hover:text-yellow-900 mt-2">
                        <span>Start Renewal Process</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if($certificate->status === 'expired')
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-gray-600 mt-0.5">event_busy</span>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">Certificate Expired</p>
                    <p class="text-sm text-gray-700 mt-1">This certificate expired on {{ $certificate->valid_until->format('d M Y') }}</p>
                    <a href="{{ route('admin.certificate-renewal.create', ['certificate_id' => $certificate->id]) }}" class="inline-flex items-center gap-1 text-sm font-medium text-gray-800 hover:text-gray-900 mt-2">
                        <span>Renew Certificate</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if($certificate->status === 'revoked')
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-red-600 mt-0.5">block</span>
                <div class="flex-1">
                    <p class="font-medium text-red-900">Certificate Revoked</p>
                    <p class="text-sm text-red-700 mt-1">This certificate was revoked on {{ $certificate->revoked_at->format('d M Y') }}</p>
                    @if($certificate->revocation_reason)
                        <p class="text-sm text-red-700 mt-1">Reason: {{ $certificate->revocation_reason }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Certificate Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Certificate Information</h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.certificates.download', $certificate) }}" class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition text-sm">
                            <span class="material-symbols-outlined text-sm">download</span>
                            <span>Download PDF</span>
                        </a>
                        @if($certificate->status !== 'revoked')
                            <a href="{{ route('admin.certificates.edit', $certificate) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition text-sm">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                <span>Edit</span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Certificate Number</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $certificate->certificate_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Competency</label>
                            <p class="mt-1 text-gray-900">{{ $certificate->competency_achieved }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Registration Number</label>
                            <p class="mt-1 text-gray-900">{{ $certificate->registration_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                @php
                                    $statusConfig = [
                                        'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Active'],
                                        'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Expired'],
                                        'revoked' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Revoked'],
                                        'suspended' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Suspended'],
                                        'renewed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Renewed'],
                                    ];
                                    $config = $statusConfig[$certificate->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $certificate->status];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Issue Date</label>
                            <p class="mt-1 text-gray-900">{{ $certificate->issue_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Valid Until</label>
                            <p class="mt-1 text-gray-900">{{ $certificate->valid_until->format('d F Y') }}</p>
                            @if($certificate->status === 'active' && !$certificate->isExpired())
                                <p class="text-sm text-gray-500">{{ $certificate->daysUntilExpiry() }} days remaining</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Validity Period</label>
                            <p class="mt-1 text-gray-900">{{ $certificate->validity_period_months }} months</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Issued By</label>
                            <p class="mt-1 text-gray-900">{{ $certificate->issuedBy->name ?? 'System' }}</p>
                        </div>
                    </div>

                    @if($certificate->previous_certificate_id)
                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500">Previous Certificate</label>
                            <a href="{{ route('admin.certificates.show', $certificate->previousCertificate) }}" class="mt-1 inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium">
                                <span>{{ $certificate->previousCertificate->certificate_number }}</span>
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                        </div>
                    @endif

                    @if($certificate->renewed_certificate_id)
                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500">Renewed As</label>
                            <a href="{{ route('admin.certificates.show', $certificate->renewedCertificate) }}" class="mt-1 inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium">
                                <span>{{ $certificate->renewedCertificate->certificate_number }}</span>
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Holder Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Certificate Holder</h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <span class="material-symbols-outlined text-blue-600 text-3xl">person</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $certificate->holder_name }}</h3>
                            @if($certificate->holder_id_number)
                                <p class="text-sm text-gray-600 mt-1">ID: {{ $certificate->holder_id_number }}</p>
                            @endif
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($certificate->assessee?->date_of_birth)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                                        <p class="mt-1 text-gray-900">{{ $certificate->assessee->date_of_birth->format('d F Y') }}</p>
                                    </div>
                                @endif
                                @if($certificate->assessee?->place_of_birth)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Place of Birth</label>
                                        <p class="mt-1 text-gray-900">{{ $certificate->assessee->place_of_birth }}</p>
                                    </div>
                                @endif
                                @if($certificate->assessee?->email)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Email</label>
                                        <p class="mt-1 text-gray-900">{{ $certificate->assessee->email }}</p>
                                    </div>
                                @endif
                                @if($certificate->assessee?->phone)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Phone</label>
                                        <p class="mt-1 text-gray-900">{{ $certificate->assessee->phone }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Assessee Link -->
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.assessees.show', $certificate->assessee) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                            <span class="material-symbols-outlined">open_in_new</span>
                            <span>View Full Assessee Profile</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Scheme & Units -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Competency Scheme & Units</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Scheme</label>
                        <div class="mt-2 flex items-start gap-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <span class="material-symbols-outlined text-purple-600">school</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $certificate->scheme->code }}</p>
                                <p class="text-sm text-gray-600">{{ $certificate->scheme->name }}</p>
                                <a href="{{ route('admin.schemes.show', $certificate->scheme) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium mt-1">
                                    <span>View Scheme Details</span>
                                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($certificate->unit_codes && count($certificate->unit_codes) > 0)
                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-3">Competency Units</label>
                            <div class="space-y-2">
                                @foreach($certificate->unit_codes as $unit)
                                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                        <span class="material-symbols-outlined text-gray-400 text-xl mt-0.5">check_circle</span>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $unit }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Assessment Result Link -->
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.assessment-results.show', $certificate->assessmentResult) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                            <span class="material-symbols-outlined">assessment</span>
                            <span>View Assessment Result</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Certificate Logs -->
            @if($certificate->logs->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Activity Log</h3>

                    <div>
                        <div class="space-y-4">
                            @foreach($certificate->logs->sortByDesc('created_at') as $log)
                                <div class="flex items-start gap-3 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div class="p-2 bg-gray-100 rounded-lg">
                                        <span class="material-symbols-outlined text-gray-600 text-sm">{{ $log->action === 'created' ? 'add_circle' : ($log->action === 'updated' ? 'edit' : 'info') }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $log->description }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $log->user->name ?? 'System' }} • {{ $log->created_at->diffForHumans() }}
                                        </p>
                                        @if($log->changes)
                                            <details class="mt-2">
                                                <summary class="text-xs text-blue-600 hover:text-blue-700 cursor-pointer">View changes</summary>
                                                <div class="mt-2 p-2 bg-gray-50 rounded text-xs font-mono">
                                                    <pre class="whitespace-pre-wrap">{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </details>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- QR Code -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">QR Code</h3>

                <div>
                    <div class="flex flex-col items-center gap-4">
                        <div class="p-4 bg-gray-100 rounded-lg">
                            <img src="{{ route('admin.certificates.generate-qr', $certificate) }}" alt="QR Code" class="w-48 h-48">
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900">{{ $certificate->qr_code }}</p>
                            <p class="text-xs text-gray-500 mt-1">Scan to verify certificate</p>
                        </div>
                        <a href="{{ $certificate->verification_url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            {{ $certificate->verification_url }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                <div class="space-y-2">
                    @if($certificate->status === 'active' || $certificate->status === 'expired')
                        <a href="{{ route('admin.certificate-renewal.create', ['certificate_id' => $certificate->id]) }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <span class="material-symbols-outlined text-blue-600">autorenew</span>
                            <span class="font-medium">Renew Certificate</span>
                        </a>
                    @endif

                    @if($certificate->status !== 'revoked')
                        <a href="{{ route('admin.certificate-revoke.create', ['certificate_id' => $certificate->id]) }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            <span class="material-symbols-outlined text-red-600">block</span>
                            <span class="font-medium">Revoke Certificate</span>
                        </a>
                    @endif

                    <button onclick="window.print()" class="w-full flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <span class="material-symbols-outlined text-gray-600">print</span>
                        <span class="font-medium">Print</span>
                    </button>

                    @if($certificate->status === 'active' && !in_array($certificate->status, ['revoked', 'expired']))
                        <form action="{{ route('admin.certificates.destroy', $certificate) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this certificate? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition">
                                <span class="material-symbols-outlined">delete</span>
                                <span class="font-medium">Delete Certificate</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Validation Stats -->
            @if($certificate->qrValidations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Validation Statistics</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Total Scans</label>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $certificate->qrValidations->count() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Last Scanned</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificate->qrValidations->sortByDesc('scanned_at')->first()->scanned_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Suspicious Scans</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $certificate->qrValidations->where('is_suspicious', true)->count() }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
