<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Jurnal Elektronik - LSP Pustaka Ilmiah Elektronik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        body {
            font-family: 'Public Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="relative flex min-h-screen w-full flex-col">
        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-200">
            <div class="container mx-auto px-4">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-blue-900 text-3xl">verified_user</span>
                        <h2 class="text-blue-900 text-xl font-bold">LSP-PIE</h2>
                    </div>
                    <nav class="hidden md:flex items-center gap-8">
                        <a class="text-sm font-medium hover:text-blue-600" href="/">Beranda</a>
                        <a class="text-sm font-medium hover:text-blue-600" href="/profile">Profil</a>
                        <a class="text-sm font-medium hover:text-blue-600" href="/struktur-organisasi">Struktur Organisasi</a>
                        <a class="text-sm font-bold text-blue-900" href="/skema">Skema</a>
                        <a class="text-sm font-medium hover:text-blue-600" href="/contact">Hubungi Kami</a>
                    </nav>
                    <div class="flex items-center gap-2">
                        <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-200 text-gray-900 text-sm font-bold tracking-wide hover:bg-gray-300">
                            <span class="truncate">Masuk</span>
                        </button>
                        <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold tracking-wide hover:bg-red-800">
                            <span class="truncate">Daftar</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
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
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-blue-900 text-white">
            <div class="container mx-auto px-4 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="md:col-span-2 lg:col-span-1">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-3xl">verified_user</span>
                            <h2 class="text-xl font-bold">LSP Pustaka Ilmiah Elektronik</h2>
                        </div>
                        <p class="mt-4 text-sm text-gray-300">LSP-PIE adalah lembaga sertifikasi resmi yang berfokus pada peningkatan kompetensi sumber daya manusia di bidang pengelolaan pustaka ilmiah, khususnya jurnal ilmiah elektronik.</p>
                    </div>
                    <div class="mt-2">
                        <h3 class="font-semibold tracking-wider uppercase">Menu</h3>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li><a class="text-gray-300 hover:text-white" href="/profile">Profil</a></li>
                            <li><a class="text-gray-300 hover:text-white" href="/struktur-organisasi">Struktur Organisasi</a></li>
                            <li><a class="text-gray-300 hover:text-white" href="/skema">Skema</a></li>
                            <li><a class="text-gray-300 hover:text-white" href="/contact">Hubungi Kami</a></li>
                        </ul>
                    </div>
                    <div class="mt-2">
                        <h3 class="font-semibold tracking-wider uppercase">Alamat</h3>
                        <p class="mt-4 text-sm text-gray-300">Jalan Raya Bibis, Ngentak, Bangunjiwo, Kel. Tamantirto, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta</p>
                    </div>
                    <div class="mt-2">
                        <h3 class="font-semibold tracking-wider uppercase">Legal</h3>
                        <ul class="mt-4 space-y-2 text-sm">
                            <li><a class="text-gray-300 hover:text-white" href="#">Terms of service</a></li>
                            <li><a class="text-gray-300 hover:text-white" href="#">Privacy policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 border-t border-white/20 pt-8 flex flex-col sm:flex-row justify-between items-center">
                    <p class="text-sm text-gray-400">© 2024 LSP Pustaka Ilmiah Elektronik. All rights reserved.</p>
                    <div class="flex mt-4 sm:mt-0 space-x-6">
                        <a class="text-gray-400 hover:text-white" href="#">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a class="text-gray-400 hover:text-white" href="#">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                        </a>
                        <a class="text-gray-400 hover:text-white" href="#">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
