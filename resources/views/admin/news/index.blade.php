@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@php
    $active = 'news';
@endphp

@section('page_title', 'Manajemen Berita')
@section('page_description', 'Kelola semua berita dan artikel')

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
                <h2 class="text-lg font-bold text-gray-900">Daftar Berita</h2>
                <p class="text-sm text-gray-600">Total: {{ $news->total() }} berita</p>
            </div>
            <a href="{{ route('admin.news.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg font-semibold transition">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Berita</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($news as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-{{ $item->icon_color }}-100 flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-{{ $item->icon_color }}-600">{{ $item->icon }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->title }}</p>
                                        <p class="text-sm text-gray-600 line-clamp-1">{{ $item->excerpt }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">{{ $item->category }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->is_published)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Published</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.news.edit', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
                            <td colspan="5" class="px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">article</span>
                                <p class="text-gray-500">Belum ada berita</p>
                                <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium text-sm mt-2">
                                    <span class="material-symbols-outlined">add</span>
                                    Tambah Berita Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($news->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $news->links() }}
            </div>
        @endif
    </div>
@endsection
