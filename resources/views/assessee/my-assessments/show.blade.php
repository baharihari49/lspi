@extends('layouts.admin')

@section('title', 'Detail Asesmen')

@php $active = 'my-assessments'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.my-assessments.index') }}" class="hover:text-blue-600">Asesmen Saya</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span>{{ $assessment->assessment_number }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Asesmen</h1>
        </div>
    </div>

    <!-- Result Banner -->
    @if($assessment->overall_result === 'competent')
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 rounded-full">
                    <span class="material-symbols-outlined text-green-600 text-3xl">verified</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-green-800">Selamat! Anda Dinyatakan KOMPETEN</h3>
                    <p class="text-green-700 mt-1">Sertifikat kompetensi Anda akan segera diterbitkan.</p>
                </div>
            </div>
        </div>
    @elseif($assessment->overall_result === 'not_yet_competent')
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-red-100 rounded-full">
                    <span class="material-symbols-outlined text-red-600 text-3xl">pending</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-red-800">Hasil: BELUM KOMPETEN</h3>
                    <p class="text-red-700 mt-1">Anda dapat mengajukan asesmen ulang setelah memenuhi rekomendasi yang diberikan.</p>
                </div>
            </div>
        </div>
    @elseif($assessment->status === 'scheduled')
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <span class="material-symbols-outlined text-yellow-600 text-3xl">event</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-yellow-800">Asesmen Terjadwal</h3>
                    <p class="text-yellow-700 mt-1">
                        Jadwal asesmen: <strong>{{ $assessment->scheduled_date?->format('d F Y') }}</strong>
                        @if($assessment->scheduled_time)
                            pukul <strong>{{ $assessment->scheduled_time->format('H:i') }}</strong>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assessment Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Asesmen</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nomor Asesmen</p>
                        <p class="font-mono font-medium text-gray-900">{{ $assessment->assessment_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'scheduled' => 'bg-yellow-100 text-yellow-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$assessment->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $assessment->status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Metode Asesmen</p>
                        <p class="font-medium text-gray-900">{{ ucfirst($assessment->assessment_method ?? '-') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tipe</p>
                        <p class="font-medium text-gray-900">{{ ucfirst($assessment->assessment_type ?? '-') }}</p>
                    </div>
                </div>
            </div>

            <!-- Scheme Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Skema Sertifikasi</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nama Skema</p>
                        <p class="font-medium text-gray-900">{{ $assessment->scheme?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kode Skema</p>
                        <p class="font-mono font-medium text-gray-900">{{ $assessment->scheme?->code ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Unit Results -->
            @if($assessment->assessmentUnits && $assessment->assessmentUnits->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Hasil Per Unit Kompetensi</h2>
                    <div class="space-y-3">
                        @foreach($assessment->assessmentUnits as $unit)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-mono text-sm text-gray-600">{{ $unit->unit_code }}</p>
                                    <p class="font-medium text-gray-900">{{ $unit->unit_title }}</p>
                                </div>
                                @php
                                    $unitResultColors = [
                                        'competent' => 'bg-green-100 text-green-800',
                                        'not_yet_competent' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $unitResultColors[$unit->result] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $unit->result === 'competent' ? 'K' : ($unit->result === 'not_yet_competent' ? 'BK' : '-') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Result Details -->
            @if($assessment->results->first())
                @php $result = $assessment->results->first(); @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Hasil</h2>
                    <div class="space-y-4">
                        @if($result->executive_summary)
                            <div>
                                <p class="text-sm text-gray-600">Ringkasan</p>
                                <p class="text-gray-700">{{ $result->executive_summary }}</p>
                            </div>
                        @endif
                        @if($result->key_strengths)
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Kekuatan</p>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    @foreach($result->key_strengths as $strength)
                                        <li>{{ $strength }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if($result->development_areas)
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Area Pengembangan</p>
                                <ul class="list-disc list-inside text-gray-700 space-y-1">
                                    @foreach($result->development_areas as $area)
                                        <li>{{ $area }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Schedule -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Jadwal</h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">calendar_today</span>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-medium text-gray-900">{{ $assessment->scheduled_date?->format('d F Y') ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">schedule</span>
                        <div>
                            <p class="text-sm text-gray-600">Waktu</p>
                            <p class="font-medium text-gray-900">{{ $assessment->scheduled_time?->format('H:i') ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Lokasi TUK</h2>
                @if($assessment->tuk)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Nama TUK</p>
                            <p class="font-medium text-gray-900">{{ $assessment->tuk->name }}</p>
                        </div>
                        @if($assessment->tuk->address)
                            <div>
                                <p class="text-sm text-gray-600">Alamat</p>
                                <p class="text-gray-700">{{ $assessment->tuk->address }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500">Lokasi belum ditentukan</p>
                @endif
            </div>

            <!-- Assessor -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Asesor</h2>
                @if($assessment->leadAssessor)
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600">person</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $assessment->leadAssessor->name }}</p>
                            <p class="text-sm text-gray-600">Lead Assessor</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">Asesor belum ditugaskan</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
