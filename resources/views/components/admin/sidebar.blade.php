@props(['active' => 'dashboard'])

<aside class="w-64 bg-blue-900 text-white flex flex-col">
    <!-- Logo/Brand -->
    <div class="px-6 py-6 border-b border-blue-800">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-3xl">verified_user</span>
            <div>
                <h2 class="text-lg font-bold">LSP-PIE</h2>
                <p class="text-xs text-blue-200">Admin Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'dashboard' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Konten</p>
        </div>

        <!-- News Management -->
        <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'news' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">article</span>
            <span class="font-medium">Berita & Artikel</span>
        </a>

        <!-- Announcements Management -->
        <a href="{{ route('admin.announcements.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'announcements' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">campaign</span>
            <span class="font-medium">Pengumuman</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Lainnya</p>
        </div>

        <!-- View Website -->
        <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-blue-100 hover:bg-blue-800">
            <span class="material-symbols-outlined">open_in_new</span>
            <span class="font-medium">Lihat Website</span>
        </a>
    </nav>

    <!-- User Info & Logout -->
    <div class="px-3 py-4 border-t border-blue-800">
        <div class="px-4 py-3 rounded-lg bg-blue-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-blue-700 flex items-center justify-center">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-200 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition text-sm font-semibold">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>
