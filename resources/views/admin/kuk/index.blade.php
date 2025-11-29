@extends('layouts.admin')

@section('title', 'KUK - Kriteria Unjuk Kerja')

@php
    $active = 'kuk';
@endphp

@section('page_title', 'Kriteria Unjuk Kerja (KUK)')
@section('page_description', 'Daftar semua elemen kompetensi dari skema sertifikasi')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar KUK</h2>
                    <p class="text-sm text-gray-600">Total: {{ $elements->total() }} elemen kompetensi</p>
                </div>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.kuk.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau judul..."
                            class="w-full h-10 pl-10 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <select name="scheme_id" id="scheme_filter" onchange="this.form.submit()"
                        class="h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">Semua Skema</option>
                        @foreach($schemes as $scheme)
                            <option value="{{ $scheme->id }}" {{ request('scheme_id') == $scheme->id ? 'selected' : '' }}>
                                {{ $scheme->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($units->count() > 0)
                    <select name="unit_id" class="h-10 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->code }} - {{ Str::limit($unit->title, 30) }}
                            </option>
                        @endforeach
                    </select>
                    @endif
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                            Filter
                        </button>
                        @if(request('search') || request('scheme_id') || request('unit_id'))
                            <a href="{{ route('admin.kuk.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul Elemen</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Kompetensi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Skema</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Kriteria</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($elements as $element)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono font-semibold text-blue-900">{{ $element->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $element->title }}</p>
                                @if($element->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($element->description, 80) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($element->schemeUnit)
                                    <p class="font-medium text-gray-900">{{ $element->schemeUnit->code }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($element->schemeUnit->title, 40) }}</p>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($element->schemeUnit?->schemeVersion?->scheme)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $element->schemeUnit->schemeVersion->scheme->code }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm">
                                    {{ $element->criteria->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.kuk.show', $element) }}"
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-flex" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">checklist</span>
                                <p class="text-gray-500">Belum ada elemen kompetensi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($elements->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $elements->links() }}
            </div>
        @endif
    </div>
@endsection
