@php
    $user = Auth::user();
    $currentRoute = Route::currentRouteName();

    // Helper function to check if menu item is active based on current route
    function isMenuItemActive($item, $currentRoute) {
        if ($currentRoute === $item['route']) {
            return true;
        }
        $routePrefix = preg_replace('/\.(index|my-reviews)$/', '', $item['route']);
        if (str_starts_with($currentRoute, $routePrefix . '.')) {
            return true;
        }
        if (str_starts_with($currentRoute, $routePrefix) && $currentRoute !== $routePrefix) {
            return true;
        }
        return false;
    }

    // Check if any child in group is active
    function isGroupActive($items, $currentRoute) {
        foreach ($items as $item) {
            if (isMenuItemActive($item, $currentRoute)) {
                return true;
            }
        }
        return false;
    }

    // Simplified menu structure - Collapsed groups
    $menuGroups = [
        // Dashboard & Modules - visible to all
        [
            'items' => [
                [
                    'route' => 'admin.dashboard',
                    'icon' => 'dashboard',
                    'label' => 'Dashboard',
                    'roles' => ['super-admin', 'admin', 'assessor', 'assessee', 'tuk-admin'],
                ],
                [
                    'route' => 'admin.modules.index',
                    'icon' => 'apps',
                    'label' => 'Modules',
                    'roles' => ['super-admin', 'admin'],
                ],
            ],
        ],

        // Sertifikasi Saya - For Assessee
        [
            'title' => 'Sertifikasi Saya',
            'icon' => 'school',
            'roles' => ['assessee'],
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.available-events.index', 'icon' => 'event_available', 'label' => 'Event Sertifikasi'],
                ['route' => 'admin.my-apl01.index', 'icon' => 'description', 'label' => 'APL-01 Saya'],
                ['route' => 'admin.my-apl02.index', 'icon' => 'folder_open', 'label' => 'APL-02 Saya'],
                ['route' => 'admin.my-assessments.index', 'icon' => 'assignment', 'label' => 'Asesmen Saya'],
                ['route' => 'admin.my-certificates.index', 'icon' => 'workspace_premium', 'label' => 'Sertifikat Saya'],
                ['route' => 'admin.my-payments.index', 'icon' => 'payments', 'label' => 'Pembayaran Saya'],
            ],
        ],

        // My Reviews - For Assessor
        [
            'title' => 'Review Saya',
            'icon' => 'rate_review',
            'roles' => ['assessor'],
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.apl01-reviews.my-reviews', 'icon' => 'assignment_ind', 'label' => 'Review APL-01'],
                ['route' => 'admin.apl02.reviews.my-reviews', 'icon' => 'person_check', 'label' => 'Review APL-02'],
                ['route' => 'admin.assessments.index', 'icon' => 'assignment', 'label' => 'Assessments', 'permission' => 'assessments.conduct'],
            ],
        ],

        // Sertifikasi - Admin (APL-01, APL-02, Assessment, Certificates)
        [
            'title' => 'Sertifikasi',
            'icon' => 'workspace_premium',
            'roles' => ['super-admin', 'admin'],
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.apl01.index', 'icon' => 'description', 'label' => 'APL-01 Forms'],
                ['route' => 'admin.apl01-reviews.index', 'icon' => 'rate_review', 'label' => 'APL-01 Reviews'],
                ['route' => 'admin.apl02.units.index', 'icon' => 'folder_open', 'label' => 'APL-02 Portfolio'],
                ['route' => 'admin.apl02.reviews.index', 'icon' => 'fact_check', 'label' => 'APL-02 Reviews'],
                ['route' => 'admin.assessments.index', 'icon' => 'assignment', 'label' => 'Assessments'],
                ['route' => 'admin.assessment-results.index', 'icon' => 'description', 'label' => 'Results & Approval'],
                ['route' => 'admin.certificates.index', 'icon' => 'workspace_premium', 'label' => 'Certificates'],
                ['route' => 'admin.certification-flow.dashboard', 'icon' => 'conversion_path', 'label' => 'Flow Dashboard'],
            ],
        ],

        // Master Data - Admin
        [
            'title' => 'Master Data',
            'icon' => 'database',
            'roles' => ['super-admin', 'admin'],
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.schemes.index', 'icon' => 'verified', 'label' => 'Skema Sertifikasi'],
                ['route' => 'admin.kuk.index', 'icon' => 'checklist', 'label' => 'KUK'],
                ['route' => 'admin.tuk.index', 'icon' => 'location_city', 'label' => 'TUK'],
                ['route' => 'admin.assessors.index', 'icon' => 'badge', 'label' => 'Assessors'],
                ['route' => 'admin.assessees.index', 'icon' => 'school', 'label' => 'Assessees'],
                ['route' => 'admin.events.index', 'icon' => 'event', 'label' => 'Events'],
            ],
        ],

        // Keuangan - Admin
        [
            'title' => 'Keuangan',
            'icon' => 'payments',
            'roles' => ['super-admin', 'admin'],
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.payments.index', 'icon' => 'payments', 'label' => 'Payments'],
                ['route' => 'admin.payment-methods.index', 'icon' => 'credit_card', 'label' => 'Payment Methods'],
            ],
        ],

        // Konten - Admin
        [
            'title' => 'Konten',
            'icon' => 'article',
            'roles' => ['super-admin', 'admin'],
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.news.index', 'icon' => 'article', 'label' => 'Berita & Artikel'],
                ['route' => 'admin.announcements.index', 'icon' => 'campaign', 'label' => 'Pengumuman'],
                ['route' => 'admin.organizational-structure.index', 'icon' => 'account_tree', 'label' => 'Struktur Organisasi'],
            ],
        ],

        // Users & Settings - Admin
        [
            'title' => 'Pengaturan',
            'icon' => 'settings',
            'roles' => ['super-admin', 'admin'],
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.users.index', 'icon' => 'people', 'label' => 'Users'],
                ['route' => 'admin.lsp-profiles.index', 'icon' => 'corporate_fare', 'label' => 'LSP Profile'],
                ['route' => 'admin.org-settings.index', 'icon' => 'settings', 'label' => 'Organization'],
                ['route' => 'admin.apl01-fields.index', 'icon' => 'dynamic_form', 'label' => 'APL-01 Fields'],
                ['route' => 'admin.master-roles.index', 'icon' => 'shield', 'label' => 'Roles & Permissions', 'roles' => ['super-admin']],
            ],
        ],

        // TUK Admin - TUK Management
        [
            'title' => 'TUK Management',
            'icon' => 'location_city',
            'roles' => ['tuk-admin'],
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.tuk.index', 'icon' => 'location_city', 'label' => 'TUK'],
                ['route' => 'admin.tuk-facilities.index', 'icon' => 'inventory_2', 'label' => 'Facilities'],
                ['route' => 'admin.tuk-documents.index', 'icon' => 'folder', 'label' => 'Documents'],
                ['route' => 'admin.tuk-schedules.index', 'icon' => 'calendar_month', 'label' => 'Schedules'],
            ],
        ],
    ];

    // Helper function to check if user can see menu item
    function canSeeMenuItem($user, $item) {
        if (isset($item['roles']) && !$user->hasAnyRole($item['roles'])) {
            return false;
        }
        if (isset($item['permission']) && !$user->hasPermission($item['permission'])) {
            return false;
        }
        return true;
    }

    // Helper function to check if user can see menu group
    function canSeeMenuGroup($user, $group) {
        if (isset($group['roles']) && !$user->hasAnyRole($group['roles'])) {
            return false;
        }
        return true;
    }

    $userRole = $user->roles->first();
    $roleName = $userRole ? $userRole->name : 'User';
@endphp

<style>
    .sidebar-nav::-webkit-scrollbar { display: none; }
    .sidebar-nav { -ms-overflow-style: none; scrollbar-width: none; }

    .menu-collapse { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
    .menu-collapse.open { max-height: 500px; }
    .menu-arrow { transition: transform 0.3s ease; }
    .menu-arrow.open { transform: rotate(180deg); }
</style>

<aside class="w-64 bg-blue-900 text-white flex flex-col">
    <!-- Logo/Brand -->
    <div class="px-6 py-5 border-b border-blue-800">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-3xl">verified_user</span>
            <div>
                <h2 class="text-lg font-bold">LSP-PIE</h2>
                <p class="text-xs text-blue-200">{{ $roleName }} Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @foreach($menuGroups as $groupIndex => $group)
            @if(canSeeMenuGroup($user, $group))
                @php
                    $visibleItems = array_filter($group['items'], function($item) use ($user) {
                        return canSeeMenuItem($user, $item);
                    });
                    $isCollapsible = isset($group['collapsible']) && $group['collapsible'];
                    $groupActive = isGroupActive($visibleItems, $currentRoute);
                @endphp

                @if(count($visibleItems) > 0)
                    @if($isCollapsible && isset($group['title']))
                        {{-- Collapsible Group --}}
                        <div class="pt-2">
                            <button type="button"
                                    onclick="toggleMenu('menu-{{ $groupIndex }}')"
                                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition {{ $groupActive ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined">{{ $group['icon'] ?? 'folder' }}</span>
                                    <span class="font-medium">{{ $group['title'] }}</span>
                                </div>
                                <span class="material-symbols-outlined text-sm menu-arrow {{ $groupActive ? 'open' : '' }}" id="arrow-{{ $groupIndex }}">expand_more</span>
                            </button>
                            <div id="menu-{{ $groupIndex }}" class="menu-collapse {{ $groupActive ? 'open' : '' }} pl-4 mt-1 space-y-1">
                                @foreach($visibleItems as $item)
                                    @if(Route::has($item['route']))
                                        <a href="{{ route($item['route']) }}"
                                           class="flex items-center gap-3 px-4 py-2 rounded-lg transition text-sm {{ isMenuItemActive($item, $currentRoute) ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                                            <span class="material-symbols-outlined text-lg">{{ $item['icon'] }}</span>
                                            <span>{{ $item['label'] }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Non-collapsible items (Dashboard, Modules) --}}
                        @foreach($visibleItems as $item)
                            @if(Route::has($item['route']))
                                <a href="{{ route($item['route']) }}"
                                   @if(isMenuItemActive($item, $currentRoute)) id="active-menu" @endif
                                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ isMenuItemActive($item, $currentRoute) ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
                                    <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                                    <span class="font-medium">{{ $item['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endif
            @endif
        @endforeach

        {{-- Akun Section --}}
        <div class="pt-4">
            <a href="{{ route('admin.profile.edit') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $currentRoute === 'admin.profile.edit' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
                <span class="material-symbols-outlined">account_circle</span>
                <span class="font-medium">Profil Saya</span>
            </a>
            <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-blue-100 hover:bg-blue-800">
                <span class="material-symbols-outlined">open_in_new</span>
                <span class="font-medium">Lihat Website</span>
            </a>
        </div>
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
                    <p class="text-xs text-blue-200 truncate">{{ $roleName }}</p>
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

<script>
    function toggleMenu(menuId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById('arrow-' + menuId.replace('menu-', ''));

        if (menu.classList.contains('open')) {
            menu.classList.remove('open');
            arrow.classList.remove('open');
        } else {
            menu.classList.add('open');
            arrow.classList.add('open');
        }
    }

    // Auto-scroll to active menu on page load
    document.addEventListener('DOMContentLoaded', function() {
        const activeMenu = document.getElementById('active-menu');
        const sidebarNav = document.querySelector('.sidebar-nav');

        if (activeMenu && sidebarNav) {
            const scrollTop = activeMenu.offsetTop - (sidebarNav.clientHeight / 2) + (activeMenu.clientHeight / 2);
            sidebarNav.scrollTo({ top: Math.max(0, scrollTop), behavior: 'instant' });
        }
    });
</script>
