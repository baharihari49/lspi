@extends('layouts.app')

@section('title', 'Berita & Artikel - LSP Pustaka Ilmiah Elektronik')

@php
    $active = 'news';
@endphp

@section('content')
    <div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-12 md:py-16">
        <div class="flex flex-col max-w-[1200px] w-full">
            <!-- Page Heading -->
            <div class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-blue-900 mb-3">Berita & Artikel</h1>
                <p class="text-lg text-gray-600">Informasi terkini, artikel, dan update seputar dunia sertifikasi profesional.</p>
            </div>

            <!-- Search & Filter Section -->
            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <!-- Search Bar -->
                <div class="flex-grow">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" placeholder="Cari berita atau artikel..." class="w-full h-12 pl-12 pr-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>
                <!-- Filter Chips -->
                <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0">
                    <button class="flex h-12 shrink-0 items-center justify-center rounded-lg bg-blue-900 text-white px-6 text-sm font-medium hover:bg-blue-800">
                        Semua
                    </button>
                    <button class="flex h-12 shrink-0 items-center justify-center rounded-lg bg-gray-200 text-gray-700 px-6 text-sm font-medium hover:bg-gray-300">
                        Artikel
                    </button>
                    <button class="flex h-12 shrink-0 items-center justify-center rounded-lg bg-gray-200 text-gray-700 px-6 text-sm font-medium hover:bg-gray-300">
                        Tips & Trik
                    </button>
                    <button class="flex h-12 shrink-0 items-center justify-center rounded-lg bg-gray-200 text-gray-700 px-6 text-sm font-medium hover:bg-gray-300">
                        Industri
                    </button>
                </div>
            </div>

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @forelse($news as $item)
                    <div class="flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Featured Image or Icon -->
                        @if($item->image)
                            <div class="w-full aspect-video overflow-hidden">
                                <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-full aspect-video bg-gradient-to-br from-{{ $item->icon_color }}-500 to-{{ $item->icon_color }}-700 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-6xl">{{ $item->icon }}</span>
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col p-6 gap-4">
                            <div class="flex flex-col gap-2">
                                <p class="text-sm text-gray-500">{{ $item->category }} • {{ $item->published_at->format('d M Y') }}</p>
                                <h3 class="text-xl font-bold text-gray-900 leading-tight">{{ $item->title }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $item->excerpt }}</p>
                            </div>
                            <a href="#" class="flex items-center justify-center rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition w-fit">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <span class="material-symbols-outlined text-gray-300 text-6xl mb-3">article</span>
                        <p class="text-gray-500 text-lg">Belum ada berita yang dipublikasikan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
                <div class="flex items-center justify-center">
                    {{ $news->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
