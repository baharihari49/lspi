@extends('layouts.admin')

@section('title', 'Detail KUK - ' . $kuk->code)

@php
    $active = 'kuk';
@endphp

@section('page_title', 'Detail Elemen Kompetensi')
@section('page_description', $kuk->code . ' - ' . $kuk->title)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Element Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 mb-2">
                            {{ $kuk->code }}
                        </span>
                        <h3 class="text-xl font-bold text-gray-900">{{ $kuk->title }}</h3>
                    </div>
                </div>

                @if($kuk->description)
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi</h4>
                        <p class="text-gray-600">{{ $kuk->description }}</p>
                    </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Unit Kompetensi</p>
                        @if($kuk->schemeUnit)
                            <p class="font-semibold text-gray-900">{{ $kuk->schemeUnit->code }}</p>
                            <p class="text-sm text-gray-600">{{ $kuk->schemeUnit->title }}</p>
                        @else
                            <p class="text-gray-400">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Skema Sertifikasi</p>
                        @if($kuk->schemeUnit?->schemeVersion?->scheme)
                            <p class="font-semibold text-gray-900">{{ $kuk->schemeUnit->schemeVersion->scheme->code }}</p>
                            <p class="text-sm text-gray-600">{{ $kuk->schemeUnit->schemeVersion->scheme->name }}</p>
                        @else
                            <p class="text-gray-400">-</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Criteria List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    Kriteria Unjuk Kerja ({{ $kuk->criteria->count() }})
                </h3>

                @if($kuk->criteria->count() > 0)
                    <div class="space-y-3">
                        @foreach($kuk->criteria as $index => $criterion)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-start gap-3">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm font-semibold">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $criterion->code ?? 'KUK ' . ($index + 1) }}</p>
                                        <p class="text-gray-600 mt-1">{{ $criterion->title }}</p>
                                        @if($criterion->description)
                                            <p class="text-sm text-gray-500 mt-2">{{ $criterion->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-300 text-4xl mb-2">checklist</span>
                        <p class="text-gray-500">Belum ada kriteria untuk elemen ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.kuk.index') }}"
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <span>Kembali ke Daftar</span>
                    </a>

                    @if($kuk->schemeUnit?->schemeVersion?->scheme)
                        <a href="{{ route('admin.schemes.show', $kuk->schemeUnit->schemeVersion->scheme) }}"
                           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-900 rounded-lg font-semibold transition">
                            <span class="material-symbols-outlined">verified</span>
                            <span>Lihat Skema</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">Jumlah Kriteria</span>
                        <span class="font-bold text-gray-900">{{ $kuk->criteria->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">Urutan</span>
                        <span class="font-bold text-gray-900">{{ $kuk->order ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Metadata</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat</span>
                        <span class="text-gray-900">{{ $kuk->created_at?->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diperbarui</span>
                        <span class="text-gray-900">{{ $kuk->updated_at?->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
