@props(['active' => 'dashboard'])

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .sidebar-nav::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .sidebar-nav {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

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
    <nav class="sidebar-nav flex-1 px-3 py-6 space-y-1 overflow-y-auto">
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

        <!-- Organizational Structure Management -->
        <a href="{{ route('admin.organizational-structure.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'organizational-structure' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">account_tree</span>
            <span class="font-medium">Struktur Organisasi</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Master Data</p>
        </div>

        <!-- Master Roles -->
        <a href="{{ route('admin.master-roles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'master-data' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">shield</span>
            <span class="font-medium">Roles & Permissions</span>
        </a>

        <!-- Master Statuses -->
        <a href="{{ route('admin.master-statuses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'master-data' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">toggle_on</span>
            <span class="font-medium">Statuses</span>
        </a>

        <!-- Master Methods -->
        <a href="{{ route('admin.master-methods.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'master-data' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">category</span>
            <span class="font-medium">Methods</span>
        </a>

        <!-- Master Document Types -->
        <a href="{{ route('admin.master-document-types.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'master-data' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">description</span>
            <span class="font-medium">Document Types</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Users</p>
        </div>

        <!-- User Management -->
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'users' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">people</span>
            <span class="font-medium">User Management</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">LSP Configuration</p>
        </div>

        <!-- LSP Profiles -->
        <a href="{{ route('admin.lsp-profiles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'lsp-profiles' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">corporate_fare</span>
            <span class="font-medium">LSP Profiles</span>
        </a>

        <!-- Organization Settings -->
        <a href="{{ route('admin.org-settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'org-settings' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">settings</span>
            <span class="font-medium">Organization Settings</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">TUK Management</p>
        </div>

        <!-- TUK -->
        <a href="{{ route('admin.tuk.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'tuk' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">location_city</span>
            <span class="font-medium">TUK</span>
        </a>

        <!-- TUK Facilities -->
        <a href="{{ route('admin.tuk-facilities.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'tuk-facilities' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">inventory_2</span>
            <span class="font-medium">TUK Facilities</span>
        </a>

        <!-- TUK Documents -->
        <a href="{{ route('admin.tuk-documents.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'tuk-documents' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">folder</span>
            <span class="font-medium">TUK Documents</span>
        </a>

        <!-- TUK Schedules -->
        <a href="{{ route('admin.tuk-schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'tuk-schedules' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">calendar_month</span>
            <span class="font-medium">TUK Schedules</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Assessor Management</p>
        </div>

        <!-- Assessors -->
        <a href="{{ route('admin.assessors.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'assessors' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">badge</span>
            <span class="font-medium">Assessors</span>
        </a>

        <!-- Assessor Documents -->
        <a href="{{ route('admin.assessor-documents.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'assessor-documents' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">description</span>
            <span class="font-medium">Documents</span>
        </a>

        <!-- Assessor Competency Scopes -->
        <a href="{{ route('admin.assessor-competency-scopes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'assessor-competency-scopes' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">workspace_premium</span>
            <span class="font-medium">Competency Scopes</span>
        </a>

        <!-- Assessor Experiences -->
        <a href="{{ route('admin.assessor-experiences.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'assessor-experiences' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">work_history</span>
            <span class="font-medium">Experiences</span>
        </a>

        <!-- Assessor Bank Info -->
        <a href="{{ route('admin.assessor-bank-info.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'assessor-bank-info' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">account_balance</span>
            <span class="font-medium">Bank Information</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Certification Schemes</p>
        </div>

        <!-- Schemes -->
        <a href="{{ route('admin.schemes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'schemes' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">verified</span>
            <span class="font-medium">Schemes</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Event Management</p>
        </div>

        <!-- Events -->
        <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'events' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">event</span>
            <span class="font-medium">Events</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Assessee Management</p>
        </div>

        <!-- Assessees -->
        <a href="{{ route('admin.assessees.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'assessees' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">school</span>
            <span class="font-medium">Assessees</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">APL-01 Forms</p>
        </div>

        <!-- APL-01 Forms -->
        <a href="{{ route('admin.apl01.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'apl01' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">description</span>
            <span class="font-medium">APL-01 Forms</span>
        </a>

        <!-- APL-01 Form Fields -->
        <a href="{{ route('admin.apl01-fields.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'apl01-fields' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">input</span>
            <span class="font-medium">Form Builder</span>
        </a>

        <!-- APL-01 Reviews -->
        <a href="{{ route('admin.apl01-reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'apl01-reviews' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">rate_review</span>
            <span class="font-medium">Reviews</span>
        </a>

        <!-- My Reviews -->
        <a href="{{ route('admin.apl01-reviews.my-reviews') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'my-reviews' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">assignment_ind</span>
            <span class="font-medium">My Reviews</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">APL-02 Portfolio</p>
        </div>

        <!-- APL-02 Units -->
        <a href="{{ route('admin.apl02.units.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'apl02-units' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">folder_open</span>
            <span class="font-medium">Portfolio Units</span>
        </a>

        <!-- APL-02 Evidence -->
        <a href="{{ route('admin.apl02.evidence.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'apl02-evidence' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">upload_file</span>
            <span class="font-medium">Evidence</span>
        </a>

        <!-- APL-02 Reviews -->
        <a href="{{ route('admin.apl02.reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'apl02-reviews' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">fact_check</span>
            <span class="font-medium">Assessor Reviews</span>
        </a>

        <!-- My APL-02 Reviews -->
        <a href="{{ route('admin.apl02.reviews.my-reviews') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'my-apl02-reviews' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">person_check</span>
            <span class="font-medium">My APL-02 Reviews</span>
        </a>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Akun</p>
        </div>

        <!-- Profile -->
        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'profile' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">settings</span>
            <span class="font-medium">Pengaturan Profil</span>
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
            <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 mb-3 hover:bg-blue-700 p-2 rounded-lg transition">
                <div class="w-10 h-10 rounded-full bg-blue-700 flex items-center justify-center">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-200 truncate">{{ Auth::user()->email }}</p>
                </div>
                <span class="material-symbols-outlined text-blue-300 text-sm">arrow_forward</span>
            </a>
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
