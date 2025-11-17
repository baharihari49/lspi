<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">@yield('page_title', 'Dashboard')</h1>
            <p class="text-sm text-gray-600">@yield('page_description', 'Selamat datang di panel admin LSP-PIE')</p>
        </div>

        <div class="flex items-center gap-4">
            <!-- Current Date/Time -->
            <div class="hidden md:block text-right">
                <p class="text-sm font-semibold text-gray-900" id="current-date"></p>
                <p class="text-xs text-gray-600" id="current-time"></p>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-center gap-2">
                <a href="/" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition" title="Lihat Website">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    function updateDateTime() {
        const now = new Date();

        // Format tanggal
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString('id-ID', options);
        document.getElementById('current-date').textContent = dateStr;

        // Format waktu
        const timeStr = now.toLocaleTimeString('id-ID');
        document.getElementById('current-time').textContent = timeStr;
    }

    // Update setiap detik
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
