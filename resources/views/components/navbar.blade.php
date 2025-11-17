@props(['active' => 'beranda'])

<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-blue-900 text-3xl">verified_user</span>
                <h2 class="text-blue-900 text-xl font-bold">LSP-PIE</h2>
            </div>
            <nav class="hidden md:flex items-center gap-8">
                <a class="text-sm font-{{ $active === 'beranda' ? 'bold text-blue-900' : 'medium hover:text-blue-600' }}" href="/">Beranda</a>
                <a class="text-sm font-{{ $active === 'profile' ? 'bold text-blue-900' : 'medium hover:text-blue-600' }}" href="/profile">Profil</a>
                <a class="text-sm font-{{ $active === 'struktur-organisasi' ? 'bold text-blue-900' : 'medium hover:text-blue-600' }}" href="/struktur-organisasi">Struktur Organisasi</a>
                <a class="text-sm font-{{ $active === 'skema' ? 'bold text-blue-900' : 'medium hover:text-blue-600' }}" href="/skema">Skema</a>
                <a class="text-sm font-{{ $active === 'news' ? 'bold text-blue-900' : 'medium hover:text-blue-600' }}" href="/news">Berita</a>
                <a class="text-sm font-{{ $active === 'pengumuman' ? 'bold text-blue-900' : 'medium hover:text-blue-600' }}" href="/pengumuman">Pengumuman</a>
                <a class="text-sm font-{{ $active === 'contact' ? 'bold text-blue-900' : 'medium hover:text-blue-600' }}" href="/contact">Hubungi Kami</a>
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
            <a class="block text-sm font-{{ $active === 'beranda' ? 'bold text-blue-900' : 'medium text-gray-900 hover:text-blue-600' }} py-2" href="/">Beranda</a>
            <a class="block text-sm font-{{ $active === 'profile' ? 'bold text-blue-900' : 'medium text-gray-900 hover:text-blue-600' }} py-2" href="/profile">Profil</a>
            <a class="block text-sm font-{{ $active === 'struktur-organisasi' ? 'bold text-blue-900' : 'medium text-gray-900 hover:text-blue-600' }} py-2" href="/struktur-organisasi">Struktur Organisasi</a>
            <a class="block text-sm font-{{ $active === 'skema' ? 'bold text-blue-900' : 'medium text-gray-900 hover:text-blue-600' }} py-2" href="/skema">Skema</a>
            <a class="block text-sm font-{{ $active === 'news' ? 'bold text-blue-900' : 'medium text-gray-900 hover:text-blue-600' }} py-2" href="/news">Berita</a>
            <a class="block text-sm font-{{ $active === 'pengumuman' ? 'bold text-blue-900' : 'medium text-gray-900 hover:text-blue-600' }} py-2" href="/pengumuman">Pengumuman</a>
            <a class="block text-sm font-{{ $active === 'contact' ? 'bold text-blue-900' : 'medium text-gray-900 hover:text-blue-600' }} py-2" href="/contact">Hubungi Kami</a>
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
