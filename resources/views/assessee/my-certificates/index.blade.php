@extends('layouts.admin')

@section('title', 'Sertifikat Saya')

@php $active = 'my-certificates'; @endphp

@section('page_title', 'Sertifikat Saya')
@section('page_description', 'Daftar sertifikat kompetensi yang Anda miliki')

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

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.my-certificates.index') }}" class="space-y-4">
            <div class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[200px] relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor sertifikat atau skema..."
                        class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                </div>

                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Dicabut</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition text-sm font-semibold">
                    Cari
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.my-certificates.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-semibold">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">workspace_premium</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Sertifikat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600">schedule</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Segera Kadaluarsa</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['expiring_soon'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-red-100 rounded-lg">
                    <span class="material-symbols-outlined text-red-600">event_busy</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kadaluarsa</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['expired'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificates Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar Sertifikat</h2>
                    <p class="text-sm text-gray-600">Total: {{ $certificates->total() }} sertifikat</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sertifikat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Skema</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Terbit</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Berlaku Sampai</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($certificates as $certificate)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-blue-900 text-3xl">workspace_premium</span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $certificate->certificate_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $certificate->holder_name ?? $certificate->assessee?->full_name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $certificate->registration_number ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $certificate->scheme?->code ?? $certificate->scheme_code ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($certificate->scheme?->name ?? $certificate->scheme_name ?? '-', 30) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                {{ $certificate->issue_date?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div>
                                    <p class="text-sm text-gray-900">{{ $certificate->valid_until?->format('d M Y') ?? '-' }}</p>
                                    @if($certificate->valid_until)
                                        @if($certificate->valid_until->isPast())
                                            <p class="text-xs text-red-600 font-medium">Kadaluarsa</p>
                                        @elseif($certificate->valid_until->diffInMonths(now()) <= 3)
                                            <p class="text-xs text-yellow-600 font-medium">{{ $certificate->valid_until->diffInDays(now()) }} hari lagi</p>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusConfig = [
                                        'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Aktif'],
                                        'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Kadaluarsa'],
                                        'revoked' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Dicabut'],
                                        'suspended' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Ditangguhkan'],
                                        'renewed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Diperpanjang'],
                                    ];
                                    $config = $statusConfig[$certificate->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => $certificate->status];
                                @endphp
                                <span class="px-3 py-1 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-xs font-semibold">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.my-certificates.show', $certificate) }}" class="p-2 text-blue-900 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>

                                    @if($certificate->pdf_path)
                                        <a href="{{ route('admin.my-certificates.download', $certificate) }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition" title="Download">
                                            <span class="material-symbols-outlined text-xl">download</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-6xl text-gray-300">workspace_premium</span>
                                    <p class="text-gray-500 font-medium">Belum ada sertifikat</p>
                                    <p class="text-sm text-gray-400">Sertifikat akan muncul setelah Anda dinyatakan kompeten</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>
@endsection
