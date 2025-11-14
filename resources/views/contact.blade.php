<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami - LSP Pustaka Ilmiah Elektronik</title>
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
                        <a class="text-sm font-medium hover:text-blue-600" href="/skema">Skema</a>
                        <a class="text-sm font-bold text-blue-900" href="/contact">Hubungi Kami</a>
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
                    <!-- Page Heading -->
                    <div class="mb-12 text-center">
                        <h1 class="text-4xl md:text-5xl font-bold text-blue-900 mb-3">Hubungi Kami</h1>
                        <p class="text-lg text-gray-600">
                            Kami siap membantu Anda. Silakan hubungi kami melalui detail di bawah ini atau kirimkan pesan melalui formulir.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column: Contact Info -->
                        <div class="flex flex-col gap-6">
                            <h2 class="text-2xl font-bold text-blue-900">LSP Pihak Ketiga</h2>
                            <p class="text-lg text-gray-700 -mt-2">LSP Pustaka Ilmiah Elektronik</p>

                            <div class="flex flex-col gap-6 mt-4">
                                <!-- Email -->
                                <div class="flex items-start gap-4">
                                    <div class="flex items-center justify-center rounded-lg bg-blue-100 shrink-0 w-12 h-12">
                                        <span class="material-symbols-outlined text-blue-900">mail</span>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <p class="text-base font-semibold text-gray-900">Email</p>
                                        <p class="text-sm text-gray-600">lsp@relawanjurnal.id</p>
                                    </div>
                                </div>

                                <!-- Telepon -->
                                <div class="flex items-start gap-4">
                                    <div class="flex items-center justify-center rounded-lg bg-blue-100 shrink-0 w-12 h-12">
                                        <span class="material-symbols-outlined text-blue-900">phone</span>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <p class="text-base font-semibold text-gray-900">Telepon</p>
                                        <p class="text-sm text-gray-600">081339595151</p>
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="flex items-start gap-4">
                                    <div class="flex items-center justify-center rounded-lg bg-blue-100 shrink-0 w-12 h-12">
                                        <span class="material-symbols-outlined text-blue-900">location_on</span>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <p class="text-base font-semibold text-gray-900">Alamat</p>
                                        <p class="text-sm text-gray-600">Jalan Raya Bibis, Ngentak, Bangunjiwo, Kel. Tamantirto, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Contact Form -->
                        <div class="bg-white rounded-xl p-6 sm:p-8 shadow-lg border border-gray-200">
                            <h2 class="text-xl font-bold text-blue-900 mb-6">Kirimkan Pesan Anda</h2>
                            <form action="#" method="POST" class="space-y-6">
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" id="email" name="email" placeholder="contoh@email.com" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                </div>

                                <div>
                                    <label for="subjek" class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                                    <input type="text" id="subjek" name="subjek" placeholder="Tuliskan subjek pesan Anda" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                </div>

                                <div>
                                    <label for="pesan" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                                    <textarea id="pesan" name="pesan" rows="4" placeholder="Tuliskan pesan Anda di sini..." class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"></textarea>
                                </div>

                                <div>
                                    <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-bold text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300">
                                        Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold text-blue-900 mb-6">Lokasi Kami</h2>
                        <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.2887894651746!2d110.32841531477736!3d-7.838397994379047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5788b8c4c3f3%3A0x5c6a6e9e8b9e4c8c!2sJl.%20Raya%20Bibis%2C%20Ngentak%2C%20Bangunjiwo%2C%20Kec.%20Kasihan%2C%20Kabupaten%20Bantul%2C%20Daerah%20Istimewa%20Yogyakarta!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid"
                                width="100%"
                                height="450"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="w-full">
                            </iframe>
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
