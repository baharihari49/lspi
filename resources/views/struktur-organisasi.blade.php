<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bagan Struktur Organisasi - LSP Pustaka Ilmiah Elektronik</title>
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

        .org-chart-node {
            position: relative;
        }

        .org-chart-node:not(:only-child):not(:first-child):before {
            content: '';
            position: absolute;
            background-color: #CFD6E7;
            left: -1rem;
            top: 50%;
            height: 1px;
            width: 1rem;
        }

        .org-chart-node:not(:only-child):not(:last-child):after {
            content: '';
            position: absolute;
            background-color: #CFD6E7;
            right: -1rem;
            top: 50%;
            height: 1px;
            width: 1rem;
        }

        .org-chart-node.has-children:before {
            content: '';
            position: absolute;
            background-color: #CFD6E7;
            left: 50%;
            bottom: -1rem;
            width: 1px;
            height: 1rem;
        }

        .org-chart-node:not(.root):after {
             content: '';
             position: absolute;
             background-color: #CFD6E7;
             left: 50%;
             top: -1rem;
             width: 1px;
             height: 1rem;
        }

        .org-chart-group:before {
            content: '';
            position: absolute;
            background-color: #CFD6E7;
            left: 0;
            top: 0;
            width: 100%;
            height: 1px;
        }

        .org-chart-group > .org-chart-node:first-child:before, .org-chart-group > .org-chart-node:last-child:after {
            display: none;
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
                        <a class="text-sm font-bold text-blue-900" href="/struktur-organisasi">Struktur Organisasi</a>
                        <a class="text-sm font-medium hover:text-blue-600" href="/skema">Skema</a>
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
            <div class="container mx-auto px-4 py-12 md:py-20">
                <!-- Page Heading -->
                <div class="mb-12 text-center md:mb-16">
                    <h1 class="text-4xl font-black tracking-tight text-blue-900 md:text-5xl">Bagan Struktur Organisasi</h1>
                    <div class="mx-auto mt-2 h-1 w-24 bg-red-700"></div>
                    <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-600">Tim profesional yang mendorong misi kami dan menjaga standar sertifikasi profesional.</p>
                </div>

                <!-- Organizational Chart -->
                <div class="flex flex-col items-center gap-y-8">
                    <!-- Level 1: Dewan Pengarah -->
                    <div class="flex justify-center">
                        <div class="org-chart-node has-children rounded-lg border border-blue-900 bg-white p-4 shadow-md text-center min-w-[220px]">
                            <h2 class="text-base font-bold text-blue-900">DEWAN PENGARAH</h2>
                            <p class="text-sm text-gray-500">Steering Committee</p>
                        </div>
                    </div>

                    <!-- Level 2: Direktur Utama -->
                    <div class="flex justify-center">
                        <div class="org-chart-node has-children rounded-lg border border-blue-900 bg-white p-4 shadow-md text-center min-w-[220px]">
                            <h2 class="text-base font-bold text-blue-900">Dwi Fajar Saputra</h2>
                            <p class="text-sm text-gray-500">Direktur Utama</p>
                        </div>
                    </div>

                    <!-- Level 3: Directors -->
                    <div class="relative w-full overflow-x-auto px-4">
                        <div class="org-chart-group relative flex justify-center gap-x-8 py-4">
                            <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                                <h2 class="text-sm font-bold">Muh Ilham Bakhtiar</h2>
                                <p class="text-xs text-gray-500">Komite Skema</p>
                            </div>
                            <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                                <h2 class="text-sm font-bold">Jamiludin Usman</h2>
                                <p class="text-xs text-gray-500">Direktur Manajemen Mutu</p>
                            </div>
                            <div class="org-chart-node has-children shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                                <h2 class="text-sm font-bold">Amardyasta G. Pratama</h2>
                                <p class="text-xs text-gray-500">Direktur Administrasi</p>
                            </div>
                            <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                                <h2 class="text-sm font-bold">Furaida Khasanah</h2>
                                <p class="text-xs text-gray-500">Direktur Keuangan</p>
                            </div>
                            <div class="org-chart-node shrink-0 rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[200px]">
                                <h2 class="text-sm font-bold">Zulidyana D. Rusnalasari</h2>
                                <p class="text-xs text-gray-500">Direktur Sertifikasi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Level 4: Manajer Representatif -->
                    <div class="flex justify-center">
                        <div class="org-chart-node rounded-lg border border-gray-300 bg-white p-4 shadow-sm text-center min-w-[220px]">
                            <h2 class="text-sm font-bold">Yusuf Saefudin</h2>
                            <p class="text-xs text-gray-500">MANAJER REPRESENTATIF</p>
                        </div>
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
