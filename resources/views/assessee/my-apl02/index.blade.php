@extends('layouts.admin')

@section('title', 'APL-02 Saya')

@php $active = 'my-apl02'; @endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">APL-02 Saya</h1>
            <p class="text-gray-600 mt-1">Daftar portfolio bukti kompetensi</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">folder_open</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Total Unit</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <span class="material-symbols-outlined text-gray-600">hourglass_empty</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Belum Mulai</p>
                    <p class="text-xl font-bold text-gray-600">{{ $stats['not_started'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <span class="material-symbols-outlined text-yellow-600">pending</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">In Progress</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['in_progress'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="material-symbols-outlined text-blue-600">send</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Submitted</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['submitted'] ?? 0 }}</p>
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
    </div>

    <!-- Units List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        @if($units->isEmpty())
            <div class="p-12 text-center">
                <span class="material-symbols-outlined text-gray-300 text-6xl">folder_open</span>
                <p class="mt-4 text-gray-500">Belum ada unit kompetensi</p>
                <p class="text-sm text-gray-400">Unit akan muncul setelah APL-01 Anda disetujui</p>
            </div>
        @else
            <div class="divide-y divide-gray-200">
                @foreach($units as $unit)
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-1 text-xs font-mono bg-gray-100 text-gray-700 rounded">{{ $unit->unit_code }}</span>
                                    @php
                                        $statusColors = [
                                            'not_started' => 'bg-gray-100 text-gray-800',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                            'submitted' => 'bg-blue-100 text-blue-800',
                                            'under_review' => 'bg-purple-100 text-purple-800',
                                            'competent' => 'bg-green-100 text-green-800',
                                            'not_yet_competent' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'not_started' => 'Belum Mulai',
                                            'in_progress' => 'Dalam Proses',
                                            'submitted' => 'Submitted',
                                            'under_review' => 'Under Review',
                                            'competent' => 'Kompeten',
                                            'not_yet_competent' => 'Belum Kompeten',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$unit->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$unit->status] ?? $unit->status }}
                                    </span>
                                </div>
                                <h3 class="font-semibold text-gray-900">{{ $unit->unit_title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($unit->unit_description, 150) }}</p>

                                <!-- Progress -->
                                <div class="mt-3 flex items-center gap-4 text-sm">
                                    <span class="text-gray-600">
                                        <span class="material-symbols-outlined text-sm align-middle">upload_file</span>
                                        {{ $unit->total_evidence ?? 0 }} Bukti
                                    </span>
                                    @if($unit->completion_percentage)
                                        <span class="text-gray-600">
                                            Progress: {{ $unit->completion_percentage }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <a href="{{ route('admin.my-apl02.show', $unit) }}"
                                    class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>
                                @if(in_array($unit->status, ['not_started', 'in_progress', 'requires_clarification']))
                                    <a href="{{ route('admin.my-apl02.upload', $unit) }}"
                                        class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Upload Bukti">
                                        <span class="material-symbols-outlined">upload</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($units->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $units->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
