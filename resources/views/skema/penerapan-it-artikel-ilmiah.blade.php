@extends('layouts.app')

@section('title', 'Penerapan IT Untuk Artikel Ilmiah - LSP Pustaka Ilmiah Elektronik')

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
                        <span class="text-gray-600">Penerapan IT Untuk Artikel Ilmiah</span>
                    </div>

                    <!-- Page Header -->
                    <div class="mb-8">
                        <div class="flex items-start space-x-4 mb-4">
                            <div class="bg-blue-100 p-4 rounded-lg">
                                <span class="material-symbols-outlined text-blue-900 text-4xl">article</span>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-bold text-blue-900 mb-2">Penerapan IT Untuk Artikel Ilmiah</h1>
                                <p class="text-lg text-gray-600 mb-2">Application IT for Scientific Article</p>
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

                        <!-- Unit 1: Mengoperasikan Perangkat Lunak Anti Plagiarisme -->
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

                        <!-- Unit 2: Mengoperasikan Perangkat Lunak Pengelolaan Sitasi -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Mengoperasikan Perangkat Lunak Pengelolaan Sitasi</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Menyiapkan pengoperasian perangkat lunak pengelolaan sitasi
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Perangkat lunak pengelolaan sitasi diidentifikasi</li>
                                            <li>Perangkat lunak pengelolaan sitasi yang akan dioperasikan ditentukan sesuai hasil identifikasi</li>
                                            <li>Komputer disiapkan sesuai kebutuhan</li>
                                            <li>Sumber-sumber rujukan yang akan disitir disiapkan sesuai kebutuhan</li>
                                            <li>Karya tulis ilmiah yang akan dikelola sitasinya disiapkan sesuai kebutuhan</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Menggunakan perangkat lunak pengelolaan sitasi
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Perangkat lunak pengelolaan sitasi dijalankan sesuai petunjuk penggunaan</li>
                                            <li>Gaya sitasi ditentukan sesuai kebutuhan karya tulis ilmiah</li>
                                            <li>Jenis Sitasi dibuat berdasarkan gaya sitasi yang telah ditentukan</li>
                                            <li>Bibliografi disusun dengan menggunakan perangkat lunak pengelolaan sitasi sesuai gaya sitasi yang ditentukan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit 3: Mengelola Struktur Metadata -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Mengelola Struktur Metadata</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Merencanakan struktur metadata koleksi perpustakaan
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Standar metadata koleksi perpustakaan yang akan digunakan ditentukan sesuai dengan kebutuhan lembaga</li>
                                            <li>Bahan perpustakaan diidentifikasi struktur metadatanya sesuai dengan jenisnya</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Menerapkan struktur metadata bibliografis
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Struktur metadata setiap jenis bahan perpustakaan ditentukan sesuai standar metdata yang dipilih</li>
                                            <li>Elemen metadata ditentukan sesuai rincian struktur metadata</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit 4: Melakukan Validasi Data Bibliografi -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Melakukan Validasi Data Bibliografi</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Menyiapkan validasi
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Bahan perpustakaan yang akan divalidasi disiapkan sesuai kebutuhan</li>
                                            <li>Data katalog di pangkalan data/master file disiapkan sesuai dengan kebutuhan</li>
                                            <li>Pedoman deskripsi bibliografis, penentuan tajuk subjek, dan notasi klasifikasi disiapkan sesuai dengan kebutuhan</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Memeriksa dan memperbaiki kebenaran data bibliografi
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Deskripsi data bibliografis bahan perpustakaan yang ada di pangkalan data diverifikasi kebenarannya sesuai standar yang berlaku</li>
                                            <li>Titik akses bahan perpustakaan diverifikasi kebenarannya sesuai standar yang berlaku</li>
                                            <li>Deskripsi data bibliografi dan titik akses yang telah diverifikasi divalidasi sesuai format standar dan pedoman yang berlaku</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit 5: Membuat Indeks -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Membuat Indeks</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Mempersiapkan pembuatan indeks
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Koleksi perpustakaan yang akan dibuatkan indeksnya dikumpulkan</li>
                                            <li>Koleksi perpustakaan diidentifikasi subjeknya sesuai dengan kebutuhan lembaga</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Menyusun naskah indeks
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Koleksi yang sudah diidentifikasi subjeknya dideskripsikan elemennya sesuai dengan ketentuan yang berlaku</li>
                                            <li>Daftar Indeks koleksi perpustakaan dibuat sesuai deskripsi elemen</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit 6: Membuat Panduan Pustaka (Pathfinder) -->
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-blue-900">Membuat Panduan Pustaka (Pathfinder)</h3>
                                <p class="text-sm text-gray-600 mt-1">SKKNI</p>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Elemen 1 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">1</span>
                                        Elemen: Mempersiapkan penyusunan
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Sarana membuat panduan pustaka disiapkan</li>
                                            <li>Kebutuhan akan panduan pustaka diidentifikasi sesuai kebutuhan</li>
                                            <li>Topik pathfinder ditentukan sesuai kebutuhan</li>
                                            <li>Bahan perpustakaan yang akan dibuatkan pathfindernya dikumpulkan sesuai dengan topik</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 2 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">2</span>
                                        Elemen: Menyusun naskah pathfinder
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Unsur informasi bahan perpustakaan yang dikumpulkan disusun sesuai ketentuan yang berlaku</li>
                                            <li>Panduan pustaka disusun sesuai unsur informasi bahan perpustakaan</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Elemen 3 -->
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2">3</span>
                                        Elemen: Melakukan pengemasan
                                    </h4>
                                    <div class="ml-8 space-y-2">
                                        <p class="font-semibold text-gray-700 text-sm">KUK (Kriteria Unjuk Kerja):</p>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Media panduan pustaka ditentukan sesuai dengan kebutuhan lembaga</li>
                                            <li>Kemasan panduan pustaka dibuat sesuai dengan media yang telah ditentukan</li>
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
