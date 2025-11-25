@extends('layouts.admin')

@section('title', 'Asesmen Saya')

@php $active = 'my-assessments'; @endphp

@section('page_title', 'Asesmen Saya')
@section('page_description', 'Daftar jadwal dan hasil asesmen kompetensi')

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

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">assignment</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600">event</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Terjadwal</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['scheduled'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">check_circle</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Selesai</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['completed'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600">verified</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Kompeten</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['competent'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-lg">
                    <span class="material-symbols-outlined text-red-600">pending</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Belum Kompeten</p>
                    <p class="text-xl font-bold text-red-600">{{ $stats['not_yet_competent'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar Asesmen</h2>
                    <p class="text-sm text-gray-600">Total: {{ $assessments->total() }} asesmen</p>
                </div>
            </div>

            <!-- Search & Filter Box -->
            <form method="GET" action="{{ route('admin.my-assessments.index') }}" class="space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') ?? '' }}" placeholder="Cari nomor asesmen..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                        Cari
                    </button>
                    @if(request()->hasAny(['search', 'status', 'result']))
                        <a href="{{ route('admin.my-assessments.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg font-semibold transition">
                            Clear
                        </a>
                    @endif
                </div>

                <!-- Filter Options -->
                <div class="flex gap-2">
                    <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">Semua Status</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>

                    <select name="result" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                        <option value="">Semua Hasil</option>
                        <option value="competent" {{ request('result') === 'competent' ? 'selected' : '' }}>Kompeten</option>
                        <option value="not_yet_competent" {{ request('result') === 'not_yet_competent' ? 'selected' : '' }}>Belum Kompeten</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Asesmen</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Skema</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Hasil</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($assessments as $assessment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">assignment</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $assessment->assessment_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $assessment->title ?? '' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="font-medium">Asesor:</span> {{ $assessment->leadAssessor->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $assessment->scheme->name ?? '-' }}</p>
                                <p class="text-sm text-gray-600">{{ $assessment->scheme->code ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $assessment->assessment_date ? $assessment->assessment_date->format('d M Y') : ($assessment->scheduled_date ? $assessment->scheduled_date->format('d M Y') : '-') }}</p>
                                <p class="text-sm text-gray-600">{{ $assessment->start_time ?? ($assessment->scheduled_time ? $assessment->scheduled_time->format('H:i') : '-') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $assessment->tuk->name ?? $assessment->venue ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'scheduled' => 'bg-blue-100 text-blue-700',
                                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                                        'completed' => 'bg-purple-100 text-purple-700',
                                        'under_review' => 'bg-orange-100 text-orange-700',
                                        'verified' => 'bg-indigo-100 text-indigo-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'cancelled' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Draft',
                                        'scheduled' => 'Terjadwal',
                                        'in_progress' => 'Sedang Berjalan',
                                        'completed' => 'Selesai',
                                        'under_review' => 'Dalam Review',
                                        'verified' => 'Terverifikasi',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                    $statusColor = $statusColors[$assessment->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $statusLabels[$assessment->status] ?? ucwords(str_replace('_', ' ', $assessment->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $resultColors = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'competent' => 'bg-green-100 text-green-700',
                                        'not_yet_competent' => 'bg-red-100 text-red-700',
                                    ];
                                    $resultLabels = [
                                        'pending' => 'Menunggu',
                                        'competent' => 'Kompeten',
                                        'not_yet_competent' => 'Belum Kompeten',
                                    ];
                                    $resultColor = $resultColors[$assessment->overall_result] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $resultColor }}">
                                    {{ $resultLabels[$assessment->overall_result] ?? ucwords(str_replace('_', ' ', $assessment->overall_result ?? 'pending')) }}
                                </span>
                                @if($assessment->overall_score)
                                    <p class="text-xs text-gray-500 mt-1">Skor: {{ number_format($assessment->overall_score, 1) }}%</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.my-assessments.show', $assessment) }}" class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">assignment</span>
                                <p class="text-gray-500 font-medium">Belum ada asesmen</p>
                                <p class="text-sm text-gray-400 mt-2">Asesmen akan dijadwalkan setelah APL-02 Anda diverifikasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assessments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $assessments->links() }}
            </div>
        @endif
    </div>
@endsection
