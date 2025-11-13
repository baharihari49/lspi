<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP Pustaka Ilmiah Elektronik - Sertifikasi Profesional</title>
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
                        <a class="text-sm font-medium hover:text-blue-600" href="#">Struktur Organisasi</a>
                        <a class="text-sm font-medium hover:text-blue-600" href="#">Skema</a>
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
                            <li><a class="text-gray-300 hover:text-white" href="#">Struktur Organisasi</a></li>
                            <li><a class="text-gray-300 hover:text-white" href="#">Skema</a></li>
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
