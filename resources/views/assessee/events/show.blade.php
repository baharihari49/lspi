@extends('layouts.admin')

@section('title', $event->name)

@php $active = 'available-events'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.available-events.index') }}" class="hover:text-blue-600">Event Sertifikasi</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span>{{ $event->code }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h1>
        </div>
        @if(!$existingRegistration && $isRegistrationOpen && ($availableSlots === null || $availableSlots > 0))
            <form action="{{ route('admin.available-events.register', $event) }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-semibold">
                    <span class="material-symbols-outlined">how_to_reg</span>
                    Daftar Sekarang
                </button>
            </form>
        @endif
    </div>

    <!-- Already Registered Banner -->
    @if($existingRegistration)
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
                <div class="flex-1">
                    <h3 class="font-semibold text-green-800">Anda sudah terdaftar di event ini</h3>
                    <p class="text-sm text-green-700">Nomor Formulir: <span class="font-mono">{{ $existingRegistration->form_number }}</span></p>
                </div>
                <a href="{{ route('admin.my-apl01.show', $existingRegistration) }}"
                    class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-semibold">
                    Lihat APL-01
                </a>
            </div>
        </div>
    @elseif(!$isRegistrationOpen)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-yellow-600 text-2xl">schedule</span>
                <div>
                    <h3 class="font-semibold text-yellow-800">Pendaftaran Ditutup</h3>
                    <p class="text-sm text-yellow-700">Periode pendaftaran: {{ $event->registration_start?->format('d M Y') }} - {{ $event->registration_end?->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    @elseif($availableSlots !== null && $availableSlots <= 0)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600 text-2xl">group_off</span>
                <div>
                    <h3 class="font-semibold text-red-800">Kuota Penuh</h3>
                    <p class="text-sm text-red-700">Kuota peserta untuk event ini sudah terpenuhi.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Event</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Kode Event</p>
                        <p class="font-mono font-medium text-gray-900">{{ $event->code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pelaksanaan</p>
                        <p class="font-medium text-gray-900">
                            {{ $event->start_date?->format('d M Y') }}
                            @if($event->end_date && $event->end_date != $event->start_date)
                                - {{ $event->end_date?->format('d M Y') }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tipe Event</p>
                        <p class="font-medium text-gray-900">{{ ucfirst($event->event_type ?? 'Reguler') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Biaya</p>
                        <p class="font-medium text-gray-900">
                            @if($event->registration_fee)
                                Rp {{ number_format($event->registration_fee, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </p>
                    </div>
                </div>

                @if($event->description)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">Deskripsi</p>
                        <p class="text-gray-700">{{ $event->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Scheme Info -->
            @if($event->scheme)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Skema Sertifikasi</h2>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama Skema</p>
                                <p class="font-medium text-gray-900">{{ $event->scheme->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kode Skema</p>
                                <p class="font-mono font-medium text-gray-900">{{ $event->scheme->code }}</p>
                            </div>
                        </div>
                        @if($event->scheme->description)
                            <div>
                                <p class="text-sm text-gray-600">Deskripsi</p>
                                <p class="text-gray-700">{{ $event->scheme->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Unit Kompetensi -->
                    @if($event->scheme->units && $event->scheme->units->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-3">Unit Kompetensi ({{ $event->scheme->units->count() }} unit)</p>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($event->scheme->units as $unit)
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="font-mono text-xs text-blue-600">{{ $unit->unit_code }}</p>
                                        <p class="text-sm text-gray-900">{{ $unit->unit_title }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Location -->
            @if($event->location || $event->location_address)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Lokasi Pelaksanaan</h2>

                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <span class="material-symbols-outlined text-blue-600">location_on</span>
                        </div>
                        <div>
                            @if($event->location)
                                <p class="font-medium text-gray-900">{{ $event->location }}</p>
                            @endif
                            @if($event->location_address)
                                <p class="text-gray-600 mt-1">{{ $event->location_address }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Registration Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Status Pendaftaran</h2>

                <div class="space-y-4">
                    <!-- Registration Period -->
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <span class="material-symbols-outlined text-gray-600">date_range</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Periode Pendaftaran</p>
                            <p class="font-medium text-gray-900">
                                {{ $event->registration_start?->format('d M') }} - {{ $event->registration_end?->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Slots -->
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <span class="material-symbols-outlined text-gray-600">groups</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Kuota Peserta</p>
                            @if($event->max_participants)
                                <div class="mt-1">
                                    <div class="flex items-center justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-900">{{ $registeredCount }} / {{ $event->max_participants }}</span>
                                        <span class="text-gray-500">{{ round(($registeredCount / $event->max_participants) * 100) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $percentage = ($registeredCount / $event->max_participants) * 100;
                                            $colorClass = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-blue-900');
                                        @endphp
                                        <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                                    </div>
                                    @if($availableSlots !== null && $availableSlots <= 5 && $availableSlots > 0)
                                        <p class="text-sm text-orange-600 mt-1 font-medium">Sisa {{ $availableSlots }} slot!</p>
                                    @endif
                                </div>
                            @else
                                <p class="font-medium text-gray-900">Tidak terbatas</p>
                            @endif
                        </div>
                    </div>

                    <!-- Registration Button -->
                    <div class="pt-4 border-t border-gray-200">
                        @if($existingRegistration)
                            <a href="{{ route('admin.my-apl01.show', $existingRegistration) }}"
                                class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-900 text-white rounded-lg font-semibold hover:bg-blue-800 transition">
                                <span class="material-symbols-outlined">description</span>
                                Lihat APL-01 Saya
                            </a>
                        @elseif(!$isRegistrationOpen)
                            <button disabled class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                                <span class="material-symbols-outlined">lock</span>
                                Pendaftaran Ditutup
                            </button>
                        @elseif($availableSlots !== null && $availableSlots <= 0)
                            <button disabled class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-medium cursor-not-allowed">
                                <span class="material-symbols-outlined">group_off</span>
                                Kuota Penuh
                            </button>
                        @else
                            <form action="{{ route('admin.available-events.register', $event) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-900 text-white rounded-lg font-semibold hover:bg-blue-800 transition">
                                    <span class="material-symbols-outlined">how_to_reg</span>
                                    Daftar Sekarang
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <h3 class="font-semibold text-yellow-900 mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined">info</span>
                    Persyaratan Pendaftaran
                </h3>
                <ul class="space-y-2 text-sm text-yellow-800">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check</span>
                        KTP/Identitas yang masih berlaku
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check</span>
                        Ijazah terakhir
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check</span>
                        Pas foto terbaru
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-sm mt-0.5">check</span>
                        Bukti pengalaman kerja (jika ada)
                    </li>
                </ul>
            </div>

            <!-- Help -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined">help</span>
                    Butuh Bantuan?
                </h3>
                <p class="text-sm text-blue-800 mb-3">Hubungi kami jika ada pertanyaan seputar pendaftaran.</p>
                <a href="/contact" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Hubungi Kami
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
