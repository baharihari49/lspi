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
                <!-- Card 1 -->
                <div class="flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="w-full aspect-video bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-6xl">article</span>
                    </div>
                    <div class="flex flex-1 flex-col p-6 gap-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-sm text-gray-500">Artikel • 15 November 2024</p>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">Pentingnya Sertifikasi di Era Digital</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">Bagaimana sertifikasi profesional dapat meningkatkan karir Anda di era transformasi digital saat ini.</p>
                        </div>
                        <button class="flex items-center justify-center rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition w-fit">
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="w-full aspect-video bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-6xl">lightbulb</span>
                    </div>
                    <div class="flex flex-1 flex-col p-6 gap-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-sm text-gray-500">Tips & Trik • 10 November 2024</p>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">Tips Sukses Menghadapi Ujian Sertifikasi</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">Strategi dan persiapan yang efektif untuk meningkatkan peluang kelulusan ujian sertifikasi kompetensi.</p>
                        </div>
                        <button class="flex items-center justify-center rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition w-fit">
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="w-full aspect-video bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-6xl">trending_up</span>
                    </div>
                    <div class="flex flex-1 flex-col p-6 gap-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-sm text-gray-500">Industri • 5 November 2024</p>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">Tren Sertifikasi Profesional 2025</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">Melihat tren dan proyeksi kebutuhan sertifikasi profesional di tahun 2025 mendatang.</p>
                        </div>
                        <button class="flex items-center justify-center rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition w-fit">
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="w-full aspect-video bg-gradient-to-br from-orange-500 to-orange-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-6xl">menu_book</span>
                    </div>
                    <div class="flex flex-1 flex-col p-6 gap-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-sm text-gray-500">Artikel • 1 November 2024</p>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">Panduan Memilih Skema Sertifikasi yang Tepat</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">Cara memilih skema sertifikasi yang sesuai dengan keahlian dan tujuan karir Anda.</p>
                        </div>
                        <button class="flex items-center justify-center rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition w-fit">
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="w-full aspect-video bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-6xl">school</span>
                    </div>
                    <div class="flex flex-1 flex-col p-6 gap-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-sm text-gray-500">Tips & Trik • 28 Oktober 2024</p>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">Belajar Efektif untuk Persiapan Sertifikasi</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">Metode belajar yang terbukti efektif untuk mempersiapkan ujian sertifikasi kompetensi.</p>
                        </div>
                        <button class="flex items-center justify-center rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition w-fit">
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="flex flex-col bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="w-full aspect-video bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-6xl">workspace_premium</span>
                    </div>
                    <div class="flex flex-1 flex-col p-6 gap-4">
                        <div class="flex flex-col gap-2">
                            <p class="text-sm text-gray-500">Artikel • 20 Oktober 2024</p>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">Manfaat Sertifikat Profesional untuk Karir</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">Mengetahui berbagai manfaat memiliki sertifikat profesional untuk pengembangan karir jangka panjang.</p>
                        </div>
                        <button class="flex items-center justify-center rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition w-fit">
                            Baca Selengkapnya
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-center gap-2">
                <button class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200 text-gray-400 cursor-not-allowed" disabled>
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-900 text-white font-bold">
                    1
                </button>
                <button class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200 text-gray-700 font-bold hover:bg-gray-300">
                    2
                </button>
                <button class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200 text-gray-700 font-bold hover:bg-gray-300">
                    3
                </button>
                <button class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endsection
