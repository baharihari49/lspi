@extends('layouts.app')

@section('title', 'Pusat Informasi & Pengumuman - LSP Pustaka Ilmiah Elektronik')

@php
    $active = 'pengumuman';
@endphp

@section('content')
    <div class="max-w-[960px] mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col gap-6">
            <!-- Page Heading -->
            <div class="flex flex-wrap justify-between gap-3 p-4">
                <div class="flex min-w-72 flex-col gap-3">
                    <h1 class="text-gray-900 text-4xl font-black tracking-tight">Pusat Informasi & Pengumuman</h1>
                    <p class="text-gray-600 text-base font-normal">Temukan informasi dan pengumuman resmi terbaru dari LSP-PIE.</p>
                </div>
            </div>

            <!-- Search Bar & Filters -->
            <div class="flex flex-col md:flex-row gap-4 px-4 py-3">
                <!-- Search Bar -->
                <div class="flex-grow">
                    <label class="flex flex-col min-w-40 h-12 w-full">
                        <div class="flex w-full flex-1 items-stretch rounded-lg h-full border border-gray-300">
                            <div class="text-gray-600 flex bg-white items-center justify-center pl-4 rounded-l-lg border-r-0">
                                <span class="material-symbols-outlined text-2xl">search</span>
                            </div>
                            <input type="text" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 focus:outline-0 focus:ring-2 focus:ring-blue-500 border-none bg-white h-full placeholder:text-gray-500 px-4 rounded-l-none border-l-0 pl-2 text-base font-normal" placeholder="Cari pengumuman..."/>
                        </div>
                    </label>
                </div>

                <!-- Category Filters -->
                <div class="flex gap-2 items-center overflow-x-auto pb-2">
                    <button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-blue-900 text-white px-4 hover:bg-blue-800 transition">
                        <p class="text-sm font-bold">Semua Kategori</p>
                    </button>
                    <button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white border border-gray-300 px-4 hover:bg-gray-50 transition">
                        <p class="text-gray-700 text-sm font-medium">Pengumuman Resmi</p>
                    </button>
                    <button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white border border-gray-300 px-4 hover:bg-gray-50 transition">
                        <p class="text-gray-700 text-sm font-medium">Jadwal Ujian</p>
                    </button>
                    <button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white border border-gray-300 px-4 hover:bg-gray-50 transition">
                        <p class="text-gray-700 text-sm font-medium">Sertifikasi</p>
                    </button>
                    <button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white border border-gray-300 px-4 hover:bg-gray-50 transition">
                        <p class="text-gray-700 text-sm font-medium">Peraturan</p>
                    </button>
                </div>
            </div>

            <!-- Announcements List -->
            <div class="flex flex-col gap-4">
                <!-- Card 1 -->
                <article class="p-4">
                    <div class="flex flex-col items-stretch justify-start rounded-lg bg-white border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex w-full flex-col items-stretch justify-center gap-2">
                            <time class="text-gray-500 text-sm font-normal" datetime="2024-11-15">Dipublikasikan pada 15 November 2024</time>
                            <h3 class="text-gray-900 text-xl font-bold tracking-tight">Pembukaan Pendaftaran Ujian Sertifikasi Q1 2025</h3>
                            <div class="flex items-end gap-4 justify-between pt-2 flex-wrap">
                                <p class="text-gray-600 text-base font-normal flex-1 min-w-[200px]">Pendaftaran ujian sertifikasi untuk periode Januari-Maret 2025 telah dibuka. Segera daftarkan diri Anda untuk mengikuti ujian kompetensi di bidang pengelolaan jurnal elektronik dan penerapan IT untuk artikel ilmiah.</p>
                                <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition">
                                    <span>Baca Selengkapnya</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Card 2 -->
                <article class="p-4">
                    <div class="flex flex-col items-stretch justify-start rounded-lg bg-white border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex w-full flex-col items-stretch justify-center gap-2">
                            <time class="text-gray-500 text-sm font-normal" datetime="2024-11-10">Dipublikasikan pada 10 November 2024</time>
                            <h3 class="text-gray-900 text-xl font-bold tracking-tight">Jadwal Lengkap Uji Kompetensi Desember 2024</h3>
                            <div class="flex items-end gap-4 justify-between pt-2 flex-wrap">
                                <p class="text-gray-600 text-base font-normal flex-1 min-w-[200px]">Berikut adalah jadwal lengkap pelaksanaan uji kompetensi untuk bulan Desember 2024 di seluruh lokasi TUK yang tersedia. Peserta diharapkan untuk memeriksa jadwal dan mempersiapkan diri dengan baik.</p>
                                <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition">
                                    <span>Baca Selengkapnya</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Card 3 -->
                <article class="p-4">
                    <div class="flex flex-col items-stretch justify-start rounded-lg bg-white border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex w-full flex-col items-stretch justify-center gap-2">
                            <time class="text-gray-500 text-sm font-normal" datetime="2024-11-05">Dipublikasikan pada 5 November 2024</time>
                            <h3 class="text-gray-900 text-xl font-bold tracking-tight">Persyaratan Baru Perpanjangan Sertifikat 2025</h3>
                            <div class="flex items-end gap-4 justify-between pt-2 flex-wrap">
                                <p class="text-gray-600 text-base font-normal flex-1 min-w-[200px]">LSP-PIE telah menetapkan persyaratan baru untuk perpanjangan sertifikat kompetensi yang berlaku mulai Januari 2025. Mohon untuk membaca dengan saksama dan mempersiapkan dokumen yang diperlukan.</p>
                                <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition">
                                    <span>Baca Selengkapnya</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Card 4 -->
                <article class="p-4">
                    <div class="flex flex-col items-stretch justify-start rounded-lg bg-white border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex w-full flex-col items-stretch justify-center gap-2">
                            <time class="text-gray-500 text-sm font-normal" datetime="2024-11-01">Dipublikasikan pada 1 November 2024</time>
                            <h3 class="text-gray-900 text-xl font-bold tracking-tight">Workshop Persiapan Ujian Sertifikasi Gratis</h3>
                            <div class="flex items-end gap-4 justify-between pt-2 flex-wrap">
                                <p class="text-gray-600 text-base font-normal flex-1 min-w-[200px]">LSP-PIE mengadakan workshop gratis untuk membantu calon asesi mempersiapkan diri mengikuti ujian sertifikasi kompetensi. Daftar sekarang karena tempat terbatas!</p>
                                <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition">
                                    <span>Baca Selengkapnya</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Card 5 -->
                <article class="p-4">
                    <div class="flex flex-col items-stretch justify-start rounded-lg bg-white border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex w-full flex-col items-stretch justify-center gap-2">
                            <time class="text-gray-500 text-sm font-normal" datetime="2024-10-28">Dipublikasikan pada 28 Oktober 2024</time>
                            <h3 class="text-gray-900 text-xl font-bold tracking-tight">Pengumuman Hasil Ujian Periode Oktober 2024</h3>
                            <div class="flex items-end gap-4 justify-between pt-2 flex-wrap">
                                <p class="text-gray-600 text-base font-normal flex-1 min-w-[200px]">Hasil ujian sertifikasi periode Oktober 2024 telah diumumkan. Peserta dapat mengecek hasil melalui portal asesi menggunakan nomor pendaftaran masing-masing.</p>
                                <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition">
                                    <span>Baca Selengkapnya</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Card 6 -->
                <article class="p-4">
                    <div class="flex flex-col items-stretch justify-start rounded-lg bg-white border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex w-full flex-col items-stretch justify-center gap-2">
                            <time class="text-gray-500 text-sm font-normal" datetime="2024-10-20">Dipublikasikan pada 20 Oktober 2024</time>
                            <h3 class="text-gray-900 text-xl font-bold tracking-tight">Panduan Lengkap Pengisian Formulir APL-01 dan APL-02</h3>
                            <div class="flex items-end gap-4 justify-between pt-2 flex-wrap">
                                <p class="text-gray-600 text-base font-normal flex-1 min-w-[200px]">Panduan terbaru untuk pengisian formulir APL-01 dan APL-02 telah tersedia untuk memudahkan calon asesi dalam proses pendaftaran. Download panduan lengkapnya di sini.</p>
                                <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-red-700 text-white text-sm font-bold hover:bg-red-800 transition">
                                    <span>Baca Selengkapnya</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6 mt-6">
                <!-- Mobile Pagination -->
                <div class="flex flex-1 justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Previous
                    </a>
                    <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Next
                    </a>
                </div>

                <!-- Desktop Pagination -->
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">6</span> of <span class="font-medium">32</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="material-symbols-outlined text-lg">chevron_left</span>
                            </a>
                            <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-900 focus:z-20">1</a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20">2</a>
                            <a href="#" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 md:inline-flex">3</a>
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>
                            <a href="#" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 md:inline-flex">6</a>
                            <a href="#" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-600 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
