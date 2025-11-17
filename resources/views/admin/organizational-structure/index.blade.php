@extends('layouts.admin')

@section('title', 'Manajemen Struktur Organisasi')

@php
    $active = 'organizational-structure';
@endphp

@section('page_title', 'Manajemen Struktur Organisasi')
@section('page_description', 'Kelola posisi dan jabatan dalam organisasi')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
            <span class="material-symbols-outlined text-green-600 mr-3">check_circle</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Daftar Posisi</h2>
                <p class="text-sm text-gray-600">Total: {{ $positions->count() }} posisi</p>
            </div>
            <a href="{{ route('admin.organizational-structure.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Posisi</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Parent</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($positions as $position)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($position->photo)
                                        <img src="{{ Storage::url($position->photo) }}" alt="{{ $position->name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-blue-600">person</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $position->name }}</p>
                                        @if($position->email)
                                            <p class="text-xs text-gray-500">{{ $position->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $position->position }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">{{ $position->level }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $position->parent ? $position->parent->name : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $position->order }}</td>
                            <td class="px-6 py-4">
                                @if($position->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.organizational-structure.edit', $position) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.organizational-structure.destroy', $position) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus posisi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">account_tree</span>
                                <p class="text-gray-500">Belum ada posisi</p>
                                <a href="{{ route('admin.organizational-structure.create') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium text-sm mt-2">
                                    <span class="material-symbols-outlined">add</span>
                                    Tambah Posisi Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
