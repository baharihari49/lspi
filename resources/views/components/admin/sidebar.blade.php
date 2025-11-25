@props(['active' => 'dashboard'])

@php
    $user = Auth::user();

    // Define menu structure with roles/permissions
    $menuGroups = [
        // Dashboard - visible to all
        [
            'items' => [
                [
                    'route' => 'admin.dashboard',
                    'icon' => 'dashboard',
                    'label' => 'Dashboard',
                    'active' => 'dashboard',
                    'roles' => ['super-admin', 'admin', 'assessor', 'assessee', 'tuk-admin'],
                ],
            ],
        ],

        // Konten - Admin only
        [
            'title' => 'Konten',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.news.index',
                    'icon' => 'article',
                    'label' => 'Berita & Artikel',
                    'active' => 'news',
                    'permission' => 'news.view',
                ],
                [
                    'route' => 'admin.announcements.index',
                    'icon' => 'campaign',
                    'label' => 'Pengumuman',
                    'active' => 'announcements',
                    'permission' => 'news.view',
                ],
                [
                    'route' => 'admin.organizational-structure.index',
                    'icon' => 'account_tree',
                    'label' => 'Struktur Organisasi',
                    'active' => 'organizational-structure',
                    'permission' => 'news.view',
                ],
            ],
        ],

        // Master Data - Super Admin only
        [
            'title' => 'Master Data',
            'roles' => ['super-admin'],
            'items' => [
                [
                    'route' => 'admin.master-roles.index',
                    'icon' => 'shield',
                    'label' => 'Roles & Permissions',
                    'active' => 'master-roles',
                    'permission' => 'roles.manage',
                ],
                [
                    'route' => 'admin.master-statuses.index',
                    'icon' => 'toggle_on',
                    'label' => 'Statuses',
                    'active' => 'master-statuses',
                    'permission' => 'roles.manage',
                ],
                [
                    'route' => 'admin.master-methods.index',
                    'icon' => 'category',
                    'label' => 'Methods',
                    'active' => 'master-methods',
                    'permission' => 'roles.manage',
                ],
                [
                    'route' => 'admin.master-document-types.index',
                    'icon' => 'description',
                    'label' => 'Document Types',
                    'active' => 'master-document-types',
                    'permission' => 'roles.manage',
                ],
            ],
        ],

        // Users - Admin only
        [
            'title' => 'Users',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.users.index',
                    'icon' => 'people',
                    'label' => 'User Management',
                    'active' => 'users',
                    'permission' => 'users.view',
                ],
            ],
        ],

        // LSP Configuration - Admin only
        [
            'title' => 'LSP Configuration',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.lsp-profiles.index',
                    'icon' => 'corporate_fare',
                    'label' => 'LSP Profiles',
                    'active' => 'lsp-profiles',
                ],
                [
                    'route' => 'admin.org-settings.index',
                    'icon' => 'settings',
                    'label' => 'Organization Settings',
                    'active' => 'org-settings',
                ],
            ],
        ],

        // TUK Management - Admin & TUK Admin
        [
            'title' => 'TUK Management',
            'roles' => ['super-admin', 'admin', 'tuk-admin'],
            'items' => [
                [
                    'route' => 'admin.tuk.index',
                    'icon' => 'location_city',
                    'label' => 'TUK',
                    'active' => 'tuk',
                    'permission' => 'tuk.view',
                ],
                [
                    'route' => 'admin.tuk-facilities.index',
                    'icon' => 'inventory_2',
                    'label' => 'TUK Facilities',
                    'active' => 'tuk-facilities',
                    'permission' => 'tuk.view',
                ],
                [
                    'route' => 'admin.tuk-documents.index',
                    'icon' => 'folder',
                    'label' => 'TUK Documents',
                    'active' => 'tuk-documents',
                    'permission' => 'tuk.view',
                ],
                [
                    'route' => 'admin.tuk-schedules.index',
                    'icon' => 'calendar_month',
                    'label' => 'TUK Schedules',
                    'active' => 'tuk-schedules',
                    'permission' => 'tuk.view',
                ],
            ],
        ],

        // Assessor Management - Admin only
        [
            'title' => 'Assessor Management',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.assessors.index',
                    'icon' => 'badge',
                    'label' => 'Assessors',
                    'active' => 'assessors',
                    'permission' => 'assessors.view',
                ],
                [
                    'route' => 'admin.assessor-documents.index',
                    'icon' => 'description',
                    'label' => 'Documents',
                    'active' => 'assessor-documents',
                    'permission' => 'assessors.view',
                ],
                [
                    'route' => 'admin.assessor-competency-scopes.index',
                    'icon' => 'workspace_premium',
                    'label' => 'Competency Scopes',
                    'active' => 'assessor-competency-scopes',
                    'permission' => 'assessors.view',
                ],
                [
                    'route' => 'admin.assessor-experiences.index',
                    'icon' => 'work_history',
                    'label' => 'Experiences',
                    'active' => 'assessor-experiences',
                    'permission' => 'assessors.view',
                ],
                [
                    'route' => 'admin.assessor-bank-info.index',
                    'icon' => 'account_balance',
                    'label' => 'Bank Information',
                    'active' => 'assessor-bank-info',
                    'permission' => 'assessors.view',
                ],
            ],
        ],

        // Certification Schemes - Admin & Assessor can view
        [
            'title' => 'Certification Schemes',
            'roles' => ['super-admin', 'admin', 'assessor'],
            'items' => [
                [
                    'route' => 'admin.schemes.index',
                    'icon' => 'verified',
                    'label' => 'Schemes',
                    'active' => 'schemes',
                    'permission' => 'schemes.view',
                ],
            ],
        ],

        // Event Management - Admin & TUK Admin
        [
            'title' => 'Event Management',
            'roles' => ['super-admin', 'admin', 'tuk-admin'],
            'items' => [
                [
                    'route' => 'admin.events.index',
                    'icon' => 'event',
                    'label' => 'Events',
                    'active' => 'events',
                ],
            ],
        ],

        // Assessee Management - Admin only
        [
            'title' => 'Assessee Management',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.assessees.index',
                    'icon' => 'school',
                    'label' => 'Assessees',
                    'active' => 'assessees',
                ],
            ],
        ],

        // My Applications - Assessee only
        [
            'title' => 'Sertifikasi Saya',
            'roles' => ['assessee', 'admin', 'super-admin'],
            'items' => [
                [
                    'route' => 'admin.available-events.index',
                    'icon' => 'event_available',
                    'label' => 'Event Sertifikasi',
                    'active' => 'available-events',
                ],
                [
                    'route' => 'admin.my-apl01.index',
                    'icon' => 'description',
                    'label' => 'APL-01 Saya',
                    'active' => 'my-apl01',
                ],
                [
                    'route' => 'admin.my-apl02.index',
                    'icon' => 'folder_open',
                    'label' => 'APL-02 Saya',
                    'active' => 'my-apl02',
                ],
                [
                    'route' => 'admin.my-assessments.index',
                    'icon' => 'assignment',
                    'label' => 'Asesmen Saya',
                    'active' => 'my-assessments',
                ],
                [
                    'route' => 'admin.my-certificates.index',
                    'icon' => 'workspace_premium',
                    'label' => 'Sertifikat Saya',
                    'active' => 'my-certificates',
                ],
            ],
        ],

        // APL-01 Forms - Admin
        [
            'title' => 'APL-01 Forms',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.apl01.index',
                    'icon' => 'description',
                    'label' => 'APL-01 Forms',
                    'active' => 'apl01',
                ],
                [
                    'route' => 'admin.apl01-fields.index',
                    'icon' => 'input',
                    'label' => 'Form Builder',
                    'active' => 'apl01-fields',
                ],
                [
                    'route' => 'admin.apl01-reviews.index',
                    'icon' => 'rate_review',
                    'label' => 'Reviews',
                    'active' => 'apl01-reviews',
                ],
            ],
        ],

        // My APL-01 Reviews - Admin & Assessor (for reviewing)
        [
            'title' => 'Review APL-01',
            'roles' => ['super-admin', 'admin', 'assessor'],
            'items' => [
                [
                    'route' => 'admin.apl01-reviews.my-reviews',
                    'icon' => 'assignment_ind',
                    'label' => 'My APL-01 Reviews',
                    'active' => 'my-reviews',
                ],
            ],
        ],

        // APL-02 Portfolio - Admin
        [
            'title' => 'APL-02 Portfolio',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.apl02.units.index',
                    'icon' => 'folder_open',
                    'label' => 'Portfolio Units',
                    'active' => 'apl02-units',
                ],
                [
                    'route' => 'admin.apl02.evidence.index',
                    'icon' => 'upload_file',
                    'label' => 'Evidence',
                    'active' => 'apl02-evidence',
                ],
                [
                    'route' => 'admin.apl02.reviews.index',
                    'icon' => 'fact_check',
                    'label' => 'Assessor Reviews',
                    'active' => 'apl02-reviews',
                ],
            ],
        ],

        // My APL-02 Reviews - Assessor
        [
            'title' => 'Review APL-02',
            'roles' => ['super-admin', 'admin', 'assessor'],
            'items' => [
                [
                    'route' => 'admin.apl02.reviews.my-reviews',
                    'icon' => 'person_check',
                    'label' => 'My APL-02 Reviews',
                    'active' => 'my-apl02-reviews',
                ],
            ],
        ],

        // Assessment Module - Admin & Assessor
        [
            'title' => 'Assessment Module',
            'roles' => ['super-admin', 'admin', 'assessor'],
            'items' => [
                [
                    'route' => 'admin.assessments.index',
                    'icon' => 'assignment',
                    'label' => 'Assessments',
                    'active' => 'assessments',
                    'permission' => 'assessments.conduct',
                ],
                [
                    'route' => 'admin.assessment-units.index',
                    'icon' => 'view_module',
                    'label' => 'Assessment Units',
                    'active' => 'assessment-units',
                    'roles' => ['super-admin', 'admin'],
                ],
                [
                    'route' => 'admin.assessment-criteria.index',
                    'icon' => 'checklist',
                    'label' => 'Criteria (KUK)',
                    'active' => 'assessment-criteria',
                    'roles' => ['super-admin', 'admin'],
                ],
                [
                    'route' => 'admin.assessment-documents.index',
                    'icon' => 'folder_open',
                    'label' => 'Documents',
                    'active' => 'assessment-documents',
                ],
                [
                    'route' => 'admin.assessment-observations.index',
                    'icon' => 'visibility',
                    'label' => 'Observations',
                    'active' => 'assessment-observations',
                ],
                [
                    'route' => 'admin.assessment-interviews.index',
                    'icon' => 'question_answer',
                    'label' => 'Interviews',
                    'active' => 'assessment-interviews',
                ],
                [
                    'route' => 'admin.assessment-verification.index',
                    'icon' => 'verified',
                    'label' => 'Verification',
                    'active' => 'assessment-verification',
                ],
            ],
        ],

        // Assessment Results - Admin & Assessor
        [
            'title' => 'Hasil Asesmen',
            'roles' => ['super-admin', 'admin', 'assessor'],
            'items' => [
                [
                    'route' => 'admin.assessment-results.index',
                    'icon' => 'description',
                    'label' => 'Results',
                    'active' => 'assessment-results',
                ],
                [
                    'route' => 'admin.result-approval.index',
                    'icon' => 'task_alt',
                    'label' => 'Result Approval',
                    'active' => 'result-approval',
                    'permission' => 'assessments.approve',
                ],
            ],
        ],

        // Sertifikasi - Admin
        [
            'title' => 'Sertifikasi',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.certificates.index',
                    'icon' => 'workspace_premium',
                    'label' => 'Certificates',
                    'active' => 'certificates',
                    'permission' => 'certificates.view',
                ],
                [
                    'route' => 'admin.certificate-renewal.index',
                    'icon' => 'autorenew',
                    'label' => 'Renewal',
                    'active' => 'certificate-renewal',
                    'permission' => 'certificates.view',
                ],
                [
                    'route' => 'admin.certificate-revoke.index',
                    'icon' => 'block',
                    'label' => 'Revocation',
                    'active' => 'certificate-revoke',
                    'permission' => 'certificates.revoke',
                ],
            ],
        ],

        // Pembayaran - Admin
        [
            'title' => 'Pembayaran',
            'roles' => ['super-admin', 'admin'],
            'items' => [
                [
                    'route' => 'admin.payments.index',
                    'icon' => 'payments',
                    'label' => 'Payments',
                    'active' => 'payments',
                    'permission' => 'payments.view',
                ],
                [
                    'route' => 'admin.payment-methods.index',
                    'icon' => 'credit_card',
                    'label' => 'Payment Methods',
                    'active' => 'payment-methods',
                    'permission' => 'payments.manage',
                ],
            ],
        ],

        // My Payments - Assessee
        [
            'title' => 'Pembayaran Saya',
            'roles' => ['assessee'],
            'items' => [
                [
                    'route' => 'admin.my-payments.index',
                    'icon' => 'payments',
                    'label' => 'Pembayaran Saya',
                    'active' => 'my-payments',
                ],
            ],
        ],
    ];

    // Helper function to check if user can see menu item
    function canSeeMenuItem($user, $item) {
        // Check role restriction on item level
        if (isset($item['roles']) && !$user->hasAnyRole($item['roles'])) {
            return false;
        }

        // Check permission
        if (isset($item['permission']) && !$user->hasPermission($item['permission'])) {
            return false;
        }

        return true;
    }

    // Helper function to check if user can see menu group
    function canSeeMenuGroup($user, $group) {
        // Check role restriction on group level
        if (isset($group['roles']) && !$user->hasAnyRole($group['roles'])) {
            return false;
        }

        return true;
    }

    // Get user's primary role for display
    $userRole = $user->roles->first();
    $roleName = $userRole ? $userRole->name : 'User';
@endphp

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
                <p class="text-xs text-blue-200">{{ $roleName }} Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav flex-1 px-3 py-6 space-y-1 overflow-y-auto">
        @foreach($menuGroups as $group)
            @if(canSeeMenuGroup($user, $group))
                @php
                    // Filter visible items in this group
                    $visibleItems = array_filter($group['items'], function($item) use ($user) {
                        return canSeeMenuItem($user, $item);
                    });
                @endphp

                @if(count($visibleItems) > 0)
                    {{-- Group Title --}}
                    @if(isset($group['title']))
                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">{{ $group['title'] }}</p>
                        </div>
                    @endif

                    {{-- Menu Items --}}
                    @foreach($visibleItems as $item)
                        @if(Route::has($item['route']))
                            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === $item['active'] ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
                                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                                <span class="font-medium">{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                @endif
            @endif
        @endforeach

        {{-- Account Section - Always visible --}}
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-wider">Akun</p>
        </div>

        <!-- Profile -->
        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'profile' ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
            <span class="material-symbols-outlined">settings</span>
            <span class="font-medium">Pengaturan Profil</span>
        </a>

        {{-- Other Section - Always visible --}}
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
