@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@php
    $active = 'dashboard';
@endphp

@section('page_title', 'Dashboard')
@section('page_description', 'Selamat datang di panel admin LSP-PIE')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total News Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 text-2xl">article</span>
                </div>
                <span class="text-3xl font-bold text-gray-900">{{ $stats['total_news'] }}</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Berita</h3>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="text-green-600 font-medium">{{ $stats['published_news'] }} Published</span>
                <span class="text-yellow-600 font-medium">{{ $stats['draft_news'] }} Draft</span>
            </div>
        </div>

        <!-- Total Announcements Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600 text-2xl">campaign</span>
                </div>
                <span class="text-3xl font-bold text-gray-900">{{ $stats['total_announcements'] }}</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Pengumuman</h3>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="text-green-600 font-medium">{{ $stats['published_announcements'] }} Published</span>
                <span class="text-yellow-600 font-medium">{{ $stats['draft_announcements'] }} Draft</span>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-gradient-to-br from-blue-900 to-blue-700 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">add_circle</span>
                </div>
            </div>
            <h3 class="text-sm font-semibold mb-4">Aksi Cepat</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.news.create') }}" class="flex items-center gap-2 text-sm hover:bg-white/10 px-3 py-2 rounded-lg transition">
                    <span class="material-symbols-outlined text-lg">add</span>
                    <span>Tambah Berita</span>
                </a>
                <a href="{{ route('admin.announcements.create') }}" class="flex items-center gap-2 text-sm hover:bg-white/10 px-3 py-2 rounded-lg transition">
                    <span class="material-symbols-outlined text-lg">add</span>
                    <span>Tambah Pengumuman</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent News -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Berita Terbaru</h2>
                <a href="{{ route('admin.news.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_news as $news)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate mb-1">{{ $news->title }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $news->excerpt }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        {{ $news->created_at->diffForHumans() }}
                                    </span>
                                    @if($news->is_published)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">Published</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full font-medium">Draft</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('admin.news.edit', $news->id) }}" class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition flex-shrink-0">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">article</span>
                        <p class="text-gray-500 text-sm">Belum ada berita</p>
                        <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium text-sm mt-2">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Tambah Berita Pertama</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Pengumuman Terbaru</h2>
                <a href="{{ route('admin.announcements.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_announcements as $announcement)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate mb-1">{{ $announcement->title }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $announcement->excerpt }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        {{ $announcement->created_at->diffForHumans() }}
                                    </span>
                                    @if($announcement->is_published)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">Published</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full font-medium">Draft</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 transition flex-shrink-0">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <span class="material-symbols-outlined text-gray-300 text-5xl mb-3">campaign</span>
                        <p class="text-gray-500 text-sm">Belum ada pengumuman</p>
                        <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-800 font-medium text-sm mt-2">
                            <span class="material-symbols-outlined text-lg">add</span>
                            <span>Tambah Pengumuman Pertama</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
