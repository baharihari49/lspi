@extends('layouts.app')

@section('title', 'LSP Pustaka Ilmiah Elektronik - Sertifikasi Profesional')

@php
    $active = 'beranda';
@endphp

@section('content')
    <!-- Hero Section -->
    <section class="relative">
        <div class="container mx-auto px-4 py-20 sm:py-24 lg:py-32">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="text-4xl font-black tracking-tight text-blue-900 sm:text-5xl lg:text-6xl">
                    Standar Keunggulan Profesional
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Tingkatkan karir Anda dengan sertifikasi profesional yang diakui secara global. Temukan, daftar, dan kelola kredensial Anda semua dalam satu tempat.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <label class="flex flex-col w-full max-w-md">
                        <div class="relative flex w-full items-stretch">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input class="w-full rounded-lg border-gray-300 bg-white h-12 pl-10 pr-4 text-base placeholder:text-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari sertifikasi...">
                        </div>
                    </label>
                    <button class="flex w-full sm:w-auto min-w-[120px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-blue-900 text-white text-base font-bold tracking-wide hover:bg-blue-800">
                        <span class="truncate">Cari</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="bg-white py-16 sm:py-24">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="flex flex-col items-center justify-center p-4">
                    <span class="text-4xl font-black text-blue-900 sm:text-5xl">2</span>
                    <p class="mt-2 text-sm font-medium text-gray-600 uppercase tracking-wider">Skema</p>
                </div>
                <div class="flex flex-col items-center justify-center p-4">
                    <span class="text-4xl font-black text-blue-900 sm:text-5xl">11</span>
                    <p class="mt-2 text-sm font-medium text-gray-600 uppercase tracking-wider">Asesor</p>
                </div>
                <div class="flex flex-col items-center justify-center p-4">
                    <span class="text-4xl font-black text-blue-900 sm:text-5xl">1</span>
                    <p class="mt-2 text-sm font-medium text-gray-600 uppercase tracking-wider">TUK</p>
                </div>
                <div class="flex flex-col items-center justify-center p-4">
                    <span class="text-4xl font-black text-red-700 sm:text-5xl">0</span>
                    <p class="mt-2 text-sm font-medium text-gray-600 uppercase tracking-wider">Asesi Kompeten</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Flow Section -->
    <section class="py-16 sm:py-24">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl text-center mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-blue-900 sm:text-4xl">Alur Pelaksanaan Uji Kompetensi</h2>
                <p class="mt-4 text-lg text-gray-600">Ikuti langkah-langkah berikut untuk mendapatkan sertifikasi kompetensi Anda.</p>
            </div>
            <div class="relative mx-auto max-w-3xl">
                <div aria-hidden="true" class="absolute left-4 top-4 h-full w-0.5 bg-gray-200"></div>
                <ul class="space-y-8">
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">1</div>
                        <p class="ml-6 text-left text-gray-600">Pastikan kamu telah memiliki akun LSP yang telah dibuat oleh admin. Gunakan akun tersebut untuk melaksanakan uji kompetensi hingga akhir!</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">2</div>
                        <p class="ml-6 text-left text-gray-600">Calon Asesi memilih skema dan mengisi form APL-01 dan APL-02 pada menu pendaftaran.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">3</div>
                        <p class="ml-6 text-left text-gray-600">Calon Asesi menunggu informasi validasi permohonan dari Admin LSP.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">4</div>
                        <p class="ml-6 text-left text-gray-600">Admin LSP mengecek kelengkapan berkas dan menginformasikannya kepada Calon Asesi.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">5</div>
                        <p class="ml-6 text-left text-gray-600">Calon Asesi akan dipasangkan dengan Asesor yang sesuai dengan skema yang dipilih.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">6</div>
                        <p class="ml-6 text-left text-gray-600">Calon Asesi dan Asesor menandatangani FR. AK-01.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">7</div>
                        <p class="ml-6 text-left text-gray-600">Admin LSP menyiapkan SPT untuk Asesor dan Verifikasi TUK.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">8</div>
                        <p class="ml-6 text-left text-gray-600">Asesi melaksanakan uji kompetensi sesuai dengan jadwal yang telah ditentukan.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">9</div>
                        <p class="ml-6 text-left text-gray-600">Hasil Uji Kompetensi sesuai berita acara UJK direkapitulasi oleh Admin LSP.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">10</div>
                        <p class="ml-6 text-left text-gray-600">Pengurus LSP mengadakan Rapat Pleno penentuan kelulusan uji kompetensi.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">11</div>
                        <p class="ml-6 text-left text-gray-600">Admin LSP mengumumkan kepada Asesi hasil uji kompetensi.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-900 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">12</div>
                        <p class="ml-6 text-left text-gray-600">LSP mengajukan permohonan Blanko Sertifikat ke BNSP.</p>
                    </li>
                    <li class="relative flex items-start">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-700 text-white font-bold ring-8 ring-gray-50 flex-shrink-0">
                            <span class="material-symbols-outlined text-lg">workspace_premium</span>
                        </div>
                        <p class="ml-6 text-left text-gray-600">Bagi Asesi yang lulus dapat mengambil sertifikasi kompetensi di kantor LSP atau di kirimkan ke alamat Asesi.</p>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Administrative Requirements Section -->
    <section class="bg-white py-16 sm:py-24">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-blue-900 sm:text-4xl">Persyaratan Administrasi</h2>
                <p class="mt-4 text-lg text-gray-600">Pastikan Anda telah menyiapkan dokumen berikut sebelum mendaftar.</p>
            </div>
            <div class="mt-12 mx-auto max-w-3xl grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex items-start p-6 bg-gray-50 rounded-xl shadow-sm">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 text-blue-900">
                        <span class="material-symbols-outlined">badge</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-blue-900">E-KTP</h3>
                        <p class="mt-1 text-gray-600">Calon Asesi mengunggah E-KTP saat mendaftar pada website LSP.</p>
                    </div>
                </div>
                <div class="flex items-start p-6 bg-gray-50 rounded-xl shadow-sm">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-lg bg-red-100 text-red-700">
                        <span class="material-symbols-outlined">account_box</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-red-700">Pas Foto 3x4 (Background Merah)</h3>
                        <p class="mt-1 text-gray-600">Calon Asesi mengunggah Pas Foto dengan background berwarna merah saat mendaftar pada website lsp.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="bg-gray-50 py-16 sm:py-24">
        <div class="container mx-auto px-4">
            <h2 class="text-center text-2xl font-bold tracking-tight text-blue-900 sm:text-3xl">Jelajahi Sertifikasi Berdasarkan Kategori</h2>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200 px-5 text-sm font-medium hover:bg-gray-300">Teknologi</button>
                <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200 px-5 text-sm font-medium hover:bg-gray-300">Kesehatan</button>
                <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200 px-5 text-sm font-medium hover:bg-gray-300">Keuangan</button>
                <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200 px-5 text-sm font-medium hover:bg-gray-300">Manajemen</button>
                <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200 px-5 text-sm font-medium hover:bg-gray-300">Teknik</button>
                <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full bg-gray-200 px-5 text-sm font-medium hover:bg-gray-300">Pendidikan</button>
            </div>
        </div>
    </section>
@endsection
