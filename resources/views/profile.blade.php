<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - LSP Pustaka Ilmiah Elektronik</title>
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
                        <a class="text-sm font-bold text-blue-900" href="/profile">Profil</a>
                        <a class="text-sm font-medium hover:text-blue-600" href="/struktur-organisasi">Struktur Organisasi</a>
                        <a class="text-sm font-medium hover:text-blue-600" href="/skema">Skema</a>
                        <a class="text-sm font-medium hover:text-blue-600" href="/contact">Hubungi Kami</a>
                    </nav>
                    <div class="hidden md:flex items-center gap-2">
                        <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-200 text-gray-900 text-sm font-bold tracking-wide hover:bg-gray-300">
                            <span class="truncate">Masuk</span>
                        </button>
                        <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold tracking-wide hover:bg-red-800">
                            <span class="truncate">Daftar</span>
                        </button>
                    </div>
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden text-blue-900 p-2">
                        <span class="material-symbols-outlined text-3xl">menu</span>
                    </button>
                </div>
            </div>
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white">
                <div class="container mx-auto px-4 py-4 space-y-3">
                    <a class="block text-sm font-medium text-gray-900 hover:text-blue-600 py-2" href="/">Beranda</a>
                    <a class="block text-sm font-bold text-blue-900 py-2" href="/profile">Profil</a>
                    <a class="block text-sm font-medium text-gray-900 hover:text-blue-600 py-2" href="/struktur-organisasi">Struktur Organisasi</a>
                    <a class="block text-sm font-medium text-gray-900 hover:text-blue-600 py-2" href="/skema">Skema</a>
                    <a class="block text-sm font-medium text-gray-900 hover:text-blue-600 py-2" href="/contact">Hubungi Kami</a>
                    <div class="pt-3 space-y-2">
                        <button class="w-full flex cursor-pointer items-center justify-center rounded-lg h-10 px-4 bg-gray-200 text-gray-900 text-sm font-bold tracking-wide hover:bg-gray-300">
                            <span class="truncate">Masuk</span>
                        </button>
                        <button class="w-full flex cursor-pointer items-center justify-center rounded-lg h-10 px-4 bg-red-700 text-white text-sm font-bold tracking-wide hover:bg-red-800">
                            <span class="truncate">Daftar</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        <script>
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            });
        </script>

        <!-- Main Content -->
        <main class="flex-grow">
            <div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-5">
                <div class="flex flex-col max-w-[960px] flex-1">
                    <div class="flex flex-col gap-8 md:gap-12 py-8 md:py-12">
                        <div class="flex flex-wrap justify-between gap-3 p-4">
                            <div class="flex w-full flex-col gap-3 text-center">
                                <p class="text-gray-900 text-4xl md:text-5xl font-black leading-tight tracking-[-0.033em]">Profil LSP Pustaka Ilmiah Elektronik</p>
                                <p class="text-blue-800 text-base font-normal leading-normal">LSP P3 Asosiasi Industri/Profesi</p>
                            </div>
                        </div>
                        <div class="px-4">
                            <div class="w-full h-64 md:h-96 overflow-hidden rounded-xl border border-gray-200">
                                <img alt="Professional meeting in a modern office" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCYyDev19jmiXPhDFiBKBZRNgrI_CRMdoRbtJDeT88WVH7SyV7rw3Xtz3v8-8A0naV2H6BdEKbRYHAxQMglTCU6n87RzZfFR7FHaTfgH8u9EuEU3M8ty1zyeppXNHe2ZniXMrTCvZJeBIBrFutSTuS-HzRrfKHuwMj6J4V7orQh9c7qTdqGCdndPeI4YLafVvZVNQoRNu-HZ2UR5GaSpxr4_arm0lsavaT_zhZKN-3Oy3e9fehl3a5GBfX_VxKM8e_YF0uSc8REWLEl"/>
                            </div>
                        </div>
                        <div class="px-4 pt-8 md:pt-4">
                            <p class="text-gray-800 text-base font-normal leading-relaxed text-center max-w-4xl mx-auto">LSP-PIE adalah lembaga sertifikasi resmi yang berfokus pada peningkatan kompetensi sumber daya manusia di bidang pengelolaan pustaka ilmiah, khususnya jurnal ilmiah elektronik. Kami hadir untuk memastikan para profesional di bidang pengelolaan jurnal seperti editor, reviewer, manajer jurnal, dan teknisi penerbitan elektronik memiliki standar kompetensi yang sesuai dengan kebutuhan industri dan perkembangan teknologi informasi. Sertifikasi yang kami selenggarakan mengacu pada Standar Kompetensi Kerja Nasional Indonesia (SKKNI) dan dirancang untuk mendukung kualitas publikasi ilmiah Indonesia yang berdaya saing global.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                            <div class="flex flex-col gap-6 md:col-span-2">
                                <div class="flex flex-col sm:flex-row gap-6 rounded-xl border border-gray-200 bg-white p-6">
                                    <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 text-blue-900 flex-shrink-0">
                                        <span class="material-symbols-outlined">satellite</span>
                                    </div>
                                    <div class="flex flex-col gap-2 text-center sm:text-left">
                                        <h2 class="text-gray-900 text-xl font-bold leading-tight">Visi</h2>
                                        <p class="text-gray-600 text-base font-normal leading-relaxed">Menjadi Lembaga Sertifikasi Profesi yang Unggul, Profesional, dan Kompeten dalam Bidang Perpustakaan dan Terbitan Ilmiah dalam skala Nasional maupun Internasional.</p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-6 rounded-xl border border-gray-200 bg-white p-6">
                                    <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 text-blue-900 flex-shrink-0">
                                        <span class="material-symbols-outlined">flutter_dash</span>
                                    </div>
                                    <div class="flex flex-col gap-2 text-center sm:text-left">
                                        <h2 class="text-gray-900 text-xl font-bold leading-tight">Misi</h2>
                                        <ul class="text-gray-600 text-base font-normal leading-relaxed list-disc pl-5 space-y-2 text-left">
                                            <li>Memberikan Pelayanan Uji Sertifikasi Kompetensi yang mengutamakan mutu dan kepuasan pelanggan.</li>
                                            <li>Memberikan jaminan bahwa proses Uji Sertifikasi dilaksanakan dengan kejujuran, teliti, tepat, akurat, effisien, dan efektif.</li>
                                            <li>Mengembangkan tersedianya tenaga kerja yang kompeten, profesional dan kompetitif di bidang Perpustakaan dan Terbitan Ilmiah.</li>
                                            <li>Mengembangkan Sarana dan Prasarana standar Kompetensi Kerja di bidang Perpustakaan dan Terbitan Ilmiah secara konsisten dan berkesinambungan sesuai dengan perkembangan dan kebutuhan industri ataupun profesi.</li>
                                            <li>Mengembangkan tata kelola tenaga Asesor kompetensi yang berkualifikasi dan bersertifikat sesuai dengan ruang lingkup sertifikasi LSP Pustaka Ilmiah Elektronik.</li>
                                            <li>Mengembangkan perangkat asesmen.</li>
                                            <li>Mengembangkan sistem pendukung berbasis teknologi dan informasi.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-6 rounded-xl border border-gray-200 bg-white p-6">
                                <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 text-blue-900 flex-shrink-0">
                                    <span class="material-symbols-outlined">trending_up</span>
                                </div>
                                <div class="flex flex-col gap-2 text-center">
                                    <h2 class="text-gray-900 text-xl font-bold leading-tight">Target</h2>
                                    <p class="text-gray-600 text-base font-normal leading-relaxed">100 asesi per tahun</p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-6 rounded-xl border border-gray-200 bg-white p-6">
                                <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 text-blue-900 flex-shrink-0">
                                    <span class="material-symbols-outlined">emoji_events</span>
                                </div>
                                <div class="flex flex-col gap-2 text-center">
                                    <h2 class="text-gray-900 text-xl font-bold leading-tight">Tujuan</h2>
                                    <p class="text-gray-600 text-base font-normal leading-relaxed">Menciptakan sumber daya manusia yang kompeten dan memiliki karakteristik unggul serta profesional di dunia terbitan berkala ilmiah.</p>
                                </div>
                            </div>
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
