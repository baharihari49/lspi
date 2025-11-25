@extends('layouts.admin')

@section('title', 'Detail Sertifikat')

@php $active = 'my-certificates'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.my-certificates.index') }}" class="hover:text-blue-600">Sertifikat Saya</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span>{{ $certificate->certificate_number }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Sertifikat</h1>
        </div>
        <div class="flex gap-2">
            @if($certificate->pdf_path)
                <a href="{{ route('admin.my-certificates.download', $certificate) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Download PDF
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Certificate Card -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-700 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <p class="text-blue-200 text-sm">Nomor Sertifikat</p>
                        <p class="text-2xl font-bold font-mono">{{ $certificate->certificate_number }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'active' => 'bg-green-500',
                            'expired' => 'bg-red-500',
                            'revoked' => 'bg-gray-500',
                            'suspended' => 'bg-yellow-500',
                        ];
                        $statusLabels = [
                            'active' => 'Aktif',
                            'expired' => 'Kadaluarsa',
                            'revoked' => 'Dicabut',
                            'suspended' => 'Ditangguhkan',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$certificate->status] ?? 'bg-gray-500' }}">
                        {{ $statusLabels[$certificate->status] ?? $certificate->status }}
                    </span>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-blue-200 text-sm">Skema Sertifikasi</p>
                        <p class="text-lg font-semibold">{{ $certificate->scheme_name ?? $certificate->scheme?->name }}</p>
                        <p class="text-blue-200">{{ $certificate->scheme_code ?? $certificate->scheme?->code }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-blue-200 text-sm">Tanggal Terbit</p>
                            <p class="font-semibold">{{ $certificate->issue_date?->format('d F Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-sm">Berlaku Sampai</p>
                            <p class="font-semibold">{{ $certificate->valid_until?->format('d F Y') ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if($certificate->qr_code_url)
                    <div class="mt-6 flex justify-center">
                        <div class="bg-white p-3 rounded-lg">
                            <img src="{{ $certificate->qr_code_url }}" alt="QR Code" class="w-32 h-32">
                        </div>
                    </div>
                @endif
            </div>

            <!-- Competency Units -->
            @if($certificate->competency_codes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Unit Kompetensi</h2>
                    <div class="space-y-3">
                        @foreach($certificate->competency_codes as $code)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600">check_circle</span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $code }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Certificate Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Sertifikat</h2>
                <div class="space-y-4">
                    @if($certificate->bnsp_registration_number)
                        <div>
                            <p class="text-sm text-gray-600">No. Registrasi BNSP</p>
                            <p class="font-medium text-gray-900">{{ $certificate->bnsp_registration_number }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Penerbit</p>
                        <p class="font-medium text-gray-900">{{ $certificate->issuing_organization ?? 'LSP-PIE' }}</p>
                    </div>
                    @if($certificate->assessment_date)
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Asesmen</p>
                            <p class="font-medium text-gray-900">{{ $certificate->assessment_date->format('d F Y') }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Masa Berlaku</p>
                        <p class="font-medium text-gray-900">{{ $certificate->validity_period_months ?? 36 }} Bulan</p>
                    </div>
                </div>
            </div>

            <!-- Verification -->
            @if($certificate->verification_url)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Verifikasi Online</h2>
                    <p class="text-sm text-gray-600 mb-4">Sertifikat ini dapat diverifikasi secara online melalui link berikut:</p>
                    <a href="{{ $certificate->verification_url }}" target="_blank"
                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                        Verifikasi Sertifikat
                    </a>
                </div>
            @endif

            <!-- Expiry Warning -->
            @if($certificate->status === 'active' && $certificate->valid_until && $certificate->valid_until->diffInMonths(now()) <= 3)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-yellow-600">warning</span>
                        <div>
                            <h3 class="font-semibold text-yellow-800">Sertifikat Segera Kadaluarsa</h3>
                            <p class="text-sm text-yellow-700 mt-1">
                                Sertifikat Anda akan kadaluarsa pada {{ $certificate->valid_until->format('d F Y') }}.
                                Silakan ajukan perpanjangan sebelum masa berlaku habis.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
