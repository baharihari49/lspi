@extends('layouts.admin')

@section('title', 'Event Sertifikasi')

@php $active = 'available-events'; @endphp

@section('page_title', 'Event Sertifikasi')
@section('page_description', 'Daftar event sertifikasi yang tersedia untuk pendaftaran')

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

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar Event</h2>
                    <p class="text-sm text-gray-600">Total: {{ $events->total() }} event tersedia</p>
                </div>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.available-events.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') ?? '' }}" placeholder="Cari nama event atau kode..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Cari
                    </button>
                    @if(request()->hasAny(['search', 'scheme_id']))
                        <a href="{{ route('admin.available-events.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-4 gap-2">
                    <select name="scheme_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">Semua Skema</option>
                        @foreach($schemes as $scheme)
                            <option value="{{ $scheme->id }}" {{ request('scheme_id') == $scheme->id ? 'selected' : '' }}>
                                {{ $scheme->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Skema</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Kuota</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Pendaftaran</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">event</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $event->name }}</p>
                                        <p class="text-sm text-gray-600 font-mono">{{ $event->code }}</p>
                                        @if($event->location)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span class="material-symbols-outlined text-xs align-middle">location_on</span>
                                                {{ $event->location }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($event->scheme)
                                    <p class="font-medium text-gray-900">{{ $event->scheme->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $event->scheme->code }}</p>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $event->start_date?->format('d M Y') }}</p>
                                @if($event->end_date && $event->end_date != $event->start_date)
                                    <p class="text-sm text-gray-600">s/d {{ $event->end_date->format('d M Y') }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ ucfirst($event->event_type ?? 'Reguler') }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($event->max_participants)
                                    @php
                                        $percentage = $event->max_participants > 0 ? (($event->current_participants ?? 0) / $event->max_participants) * 100 : 0;
                                        $colorClass = $percentage >= 90 ? 'text-red-600' : ($percentage >= 70 ? 'text-yellow-600' : 'text-green-600');
                                    @endphp
                                    <p class="font-bold {{ $colorClass }}">{{ $event->current_participants ?? 0 }}/{{ $event->max_participants }}</p>
                                    <div class="w-20 mx-auto mt-1 bg-gray-200 rounded-full h-1.5">
                                        @php
                                            $barColor = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                        @endphp
                                        <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Tidak terbatas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-sm text-gray-900">{{ $event->registration_start?->format('d M') }}</p>
                                <p class="text-sm text-gray-600">s/d {{ $event->registration_end?->format('d M Y') }}</p>
                                @if($event->registration_fee)
                                    <p class="text-xs text-blue-600 font-medium mt-1">Rp {{ number_format($event->registration_fee, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-xs text-green-600 font-medium mt-1">Gratis</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(in_array($event->id, $registeredEventIds))
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-semibold">
                                            <span class="material-symbols-outlined text-base">check_circle</span>
                                            Terdaftar
                                        </span>
                                    @else
                                        <a href="{{ route('admin.available-events.show', $event) }}" class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">event_busy</span>
                                <p class="text-gray-500 font-medium">Tidak ada event yang tersedia</p>
                                <p class="text-sm text-gray-400 mt-2">Silakan cek kembali nanti untuk event sertifikasi terbaru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection
