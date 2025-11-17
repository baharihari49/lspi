@extends('layouts.app')

@section('title', 'Pengelolaan Jurnal Elektronik - LSP Pustaka Ilmiah Elektronik')

@php
    $active = 'skema';
@endphp

@section('content')
            <div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-12 md:py-16">
                <div class="flex flex-col max-w-[1200px] w-full">
                    <!-- Breadcrumb -->
                    <div class="flex items-center space-x-2 mb-6 text-sm">
                        <a href="/skema" class="text-blue-600 hover:text-blue-800">Skema</a>
                        <span class="text-gray-400">/</span>
                        <span class="text-gray-600">Pengelolaan Jurnal Elektronik</span>
                    </div>

                    <!-- Page Header -->
                    <div class="mb-8">
                        <div class="flex items-start space-x-4 mb-4">
                            <div class="bg-blue-100 p-4 rounded-lg">
                                <span class="material-symbols-outlined text-blue-900 text-4xl">menu_book</span>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-bold text-blue-900 mb-2">Pengelolaan Jurnal Elektronik</h1>
                                <p class="text-lg text-gray-600 mb-2">Electronic Journal Management</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-block bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1 rounded-full">Bidang Perpustakaan (Library)</span>
                                    <span class="inline-block bg-blue-100 text-blue-900 text-sm font-bold px-3 py-1 rounded-full">KKNI (LEVEL 3)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit SKKNI List -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Daftar Unit Skema</h2>

                        <!-- Unit 1: Melakukan Analisis Subjek -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Melakukan Analisis Subjek</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Melakukan persiapan analisis subjek bahan perpustakaan
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Bahan perpustakaan yang akan dianalisis subjeknya disiapkan</li>
                                            <li>Pedoman analisis subjek disiapkan</li>
                                            <li>Alat pendukung analisis subjek disiapkan</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Menentukan subjek bahan perpustakaan
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Bahan perpustakaan yang akan dianalisis subjeknya diidentifikasi untuk menentukan konsep subjeknya</li>
                                            <li>Konsep subjek bahan perpustakaan diidentifikasi untuk menentukan jenis subjeknya</li>
                                            <li>Subjek bahan perpustakaan ditetapkan sesuai pedoman penentuan subjek</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit 2: Mengelola E-Resources -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Mengelola E-Resources</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Menyiapkan pengelolaan e-resources
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Kebutuhan terhadap e-resources diidentifikasi</li>
                                            <li>Sumber e-resources ditentukan sesuai kebutuhan</li>
                                            <li>Alat dan bahan disiapkan sesuai kebutuhan</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Melakukan pengelolaan e-resources
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Koleksi e-resources disambungkan dengan sistem informasi perpustakaan sesuai petunjuk penggunaan</li>
                                            <li>Hak akses ke koleksi e-resources ditentukan</li>
                                            <li>Koleksi e-resources dilayankan ke pemustaka melalui sistem informasi perpustakaan sesuai petunjuk penggunanaan</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 3 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">3</span>
                                        Elemen: Mengevaluasi pemanfaatan e-resources
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Data penggunaan e-resources dikumpulkan sesuai kebutuhan</li>
                                            <li>Data penggunaan e-resources yang telah dikumpulkan dianalisis</li>
                                            <li>Tindak lanjut hasil analisis ditentukan sesuai kebutuhan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit 3: Mengelola Jurnal Elektronik -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Mengelola Jurnal Elektronik</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Menyiapkan pengelolaan aplikasi jurnal elektronik
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Kebutuhan terhadap jurnal elektronik dianalisis</li>
                                            <li>Panduan pengelolaan jurnal elektronik disiapkan sesuai kebutuhan</li>
                                            <li>Perangkat dan perlengkapan untuk pengelolaan jurnal elektronik disiapkan sesuai kebutuhan</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Melakukan pengelolaan jurnal elektronik
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Set-up jurnal elektronik dilakukan petunjuk penggunaan</li>
                                            <li>Kebijakan pengelolaan jurnal elektronik ditetapkan berdasarkan panduan pengelolaan</li>
                                            <li>Sistem submisi diatur/di-setting sesuai kebutuhan</li>
                                            <li>Jurnal elektronik dipromosikan sesuai kebutuhan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit 4: Mengoperasikan Perangkat Lunak Anti Plagiarisme -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Mengoperasikan Perangkat Lunak Anti Plagiarisme</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Menyiapkan pengoperasian perangkat lunak anti plagiarisme
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Perangkat lunak anti plagiarisme diidentifikasi</li>
                                            <li>Perangkat lunak anti plagiarisme yang akan dioperasikan ditentukan sesuai hasil identifikasi</li>
                                            <li>Komputer dan perangkat pendukung disiapkan sesuai kebutuhan</li>
                                            <li>Karya tulis atau artikel yang akan dicek disiapkan sesuai kebutuhan</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Menggunakan perangkat lunak anti plagiariasme
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Perangkat lunak anti plagiarisme dijalankan sesuai petunjuk penggunaan</li>
                                            <li>Format dokumen karya tulis yang akan dicek disesuaikan dengan perangkat lunak anti plagiarisme</li>
                                            <li>Karya tulis yang akan dicek diunggah ke dalam perangkat lunak anti plagiarisme</li>
                                            <li>Parameter yang dibutuhkan dalam perangkat lunak anti plagiarisme disesuaikan dengan kebutuhan</li>
                                            <li>Hasil pengecekan suatu karya oleh perangkat lunak anti plagiarisme dianalisis</li>
                                            <li>Rekomendasi dari hasil analisis dibuat berdasarkan ketentuan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="mt-8">
                        <a href="/skema" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-bold py-2.5 px-6 rounded-lg transition duration-300">
                            <span class="material-symbols-outlined">arrow_back</span>
                            Kembali ke Daftar Skema
                        </a>
                    </div>
                </div>
@endsection
