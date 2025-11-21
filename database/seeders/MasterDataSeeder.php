<?php

namespace Database\Seeders;

use App\Models\MasterDocumentType;
use App\Models\MasterMethod;
use App\Models\MasterPermission;
use App\Models\MasterRole;
use App\Models\MasterStatus;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedRoles();
        $this->seedPermissions();
        $this->seedRolePermissions();
        $this->seedStatuses();
        $this->seedMethods();
        $this->seedDocumentTypes();
    }

    private function seedRoles(): void
    {
        $roles = [
            [
                'slug' => 'super-admin',
                'name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
            ],
            [
                'slug' => 'admin',
                'name' => 'Administrator',
                'description' => 'Manage LSP operations and users',
            ],
            [
                'slug' => 'assessor',
                'name' => 'Assessor',
                'description' => 'Conduct assessments and review portfolios',
            ],
            [
                'slug' => 'assessee',
                'name' => 'Assessee',
                'description' => 'Participant seeking certification',
            ],
            [
                'slug' => 'tuk-admin',
                'name' => 'TUK Administrator',
                'description' => 'Manage TUK facilities and schedules',
            ],
        ];

        foreach ($roles as $role) {
            MasterRole::create($role);
        }
    }

    private function seedPermissions(): void
    {
        $permissions = [
            // User Management
            ['slug' => 'users.view', 'name' => 'View Users', 'description' => 'View user list and details'],
            ['slug' => 'users.create', 'name' => 'Create Users', 'description' => 'Create new users'],
            ['slug' => 'users.edit', 'name' => 'Edit Users', 'description' => 'Edit user information'],
            ['slug' => 'users.delete', 'name' => 'Delete Users', 'description' => 'Delete users'],

            // Role & Permission Management
            ['slug' => 'roles.view', 'name' => 'View Roles', 'description' => 'View roles and permissions'],
            ['slug' => 'roles.manage', 'name' => 'Manage Roles', 'description' => 'Create, edit, delete roles'],

            // News Management
            ['slug' => 'news.view', 'name' => 'View News', 'description' => 'View news articles'],
            ['slug' => 'news.manage', 'name' => 'Manage News', 'description' => 'Create, edit, delete news'],

            // Scheme Management
            ['slug' => 'schemes.view', 'name' => 'View Schemes', 'description' => 'View certification schemes'],
            ['slug' => 'schemes.manage', 'name' => 'Manage Schemes', 'description' => 'Create, edit, delete schemes'],

            // TUK Management
            ['slug' => 'tuk.view', 'name' => 'View TUK', 'description' => 'View TUK list and details'],
            ['slug' => 'tuk.manage', 'name' => 'Manage TUK', 'description' => 'Create, edit, delete TUK'],

            // Assessor Management
            ['slug' => 'assessors.view', 'name' => 'View Assessors', 'description' => 'View assessor list'],
            ['slug' => 'assessors.manage', 'name' => 'Manage Assessors', 'description' => 'Manage assessor data'],

            // Assessment
            ['slug' => 'assessments.conduct', 'name' => 'Conduct Assessment', 'description' => 'Perform assessments'],
            ['slug' => 'assessments.approve', 'name' => 'Approve Assessment', 'description' => 'Approve assessment results'],

            // Certificate Management
            ['slug' => 'certificates.view', 'name' => 'View Certificates', 'description' => 'View certificates'],
            ['slug' => 'certificates.issue', 'name' => 'Issue Certificates', 'description' => 'Issue certificates'],
            ['slug' => 'certificates.revoke', 'name' => 'Revoke Certificates', 'description' => 'Revoke certificates'],

            // Payment Management
            ['slug' => 'payments.view', 'name' => 'View Payments', 'description' => 'View payment transactions'],
            ['slug' => 'payments.manage', 'name' => 'Manage Payments', 'description' => 'Manage payment transactions'],

            // Reports
            ['slug' => 'reports.view', 'name' => 'View Reports', 'description' => 'View system reports'],
        ];

        foreach ($permissions as $permission) {
            MasterPermission::create($permission);
        }
    }

    private function seedRolePermissions(): void
    {
        // Super Admin gets all permissions
        $superAdmin = MasterRole::where('slug', 'super-admin')->first();
        $allPermissions = MasterPermission::all();
        $superAdmin->permissions()->attach($allPermissions->pluck('id'));

        // Admin gets most permissions
        $admin = MasterRole::where('slug', 'admin')->first();
        $adminPermissions = MasterPermission::whereIn('slug', [
            'users.view', 'users.create', 'users.edit',
            'news.view', 'news.manage',
            'schemes.view', 'schemes.manage',
            'tuk.view', 'tuk.manage',
            'assessors.view', 'assessors.manage',
            'assessments.approve',
            'certificates.view', 'certificates.issue',
            'payments.view', 'payments.manage',
            'reports.view',
        ])->get();
        $admin->permissions()->attach($adminPermissions->pluck('id'));

        // Assessor permissions
        $assessor = MasterRole::where('slug', 'assessor')->first();
        $assessorPermissions = MasterPermission::whereIn('slug', [
            'schemes.view',
            'assessments.conduct',
            'certificates.view',
        ])->get();
        $assessor->permissions()->attach($assessorPermissions->pluck('id'));

        // TUK Admin permissions
        $tukAdmin = MasterRole::where('slug', 'tuk-admin')->first();
        $tukPermissions = MasterPermission::whereIn('slug', [
            'tuk.view', 'tuk.manage',
        ])->get();
        $tukAdmin->permissions()->attach($tukPermissions->pluck('id'));
    }

    private function seedStatuses(): void
    {
        $statuses = [
            // User statuses
            ['category' => 'user', 'code' => 'active', 'label' => 'Active', 'description' => 'User is active', 'sort_order' => 1],
            ['category' => 'user', 'code' => 'inactive', 'label' => 'Inactive', 'description' => 'User is inactive', 'sort_order' => 2],
            ['category' => 'user', 'code' => 'suspended', 'label' => 'Suspended', 'description' => 'User is suspended', 'sort_order' => 3],

            // Assessment statuses
            ['category' => 'assessment', 'code' => 'scheduled', 'label' => 'Scheduled', 'description' => 'Assessment is scheduled', 'sort_order' => 1],
            ['category' => 'assessment', 'code' => 'in-progress', 'label' => 'In Progress', 'description' => 'Assessment is in progress', 'sort_order' => 2],
            ['category' => 'assessment', 'code' => 'completed', 'label' => 'Completed', 'description' => 'Assessment completed', 'sort_order' => 3],
            ['category' => 'assessment', 'code' => 'cancelled', 'label' => 'Cancelled', 'description' => 'Assessment cancelled', 'sort_order' => 4],

            // APL statuses
            ['category' => 'apl', 'code' => 'draft', 'label' => 'Draft', 'description' => 'Form is being drafted', 'sort_order' => 1],
            ['category' => 'apl', 'code' => 'submitted', 'label' => 'Submitted', 'description' => 'Form submitted for review', 'sort_order' => 2],
            ['category' => 'apl', 'code' => 'under-review', 'label' => 'Under Review', 'description' => 'Form is being reviewed', 'sort_order' => 3],
            ['category' => 'apl', 'code' => 'approved', 'label' => 'Approved', 'description' => 'Form approved', 'sort_order' => 4],
            ['category' => 'apl', 'code' => 'rejected', 'label' => 'Rejected', 'description' => 'Form rejected', 'sort_order' => 5],

            // Certificate statuses
            ['category' => 'certificate', 'code' => 'active', 'label' => 'Active', 'description' => 'Certificate is active', 'sort_order' => 1],
            ['category' => 'certificate', 'code' => 'expired', 'label' => 'Expired', 'description' => 'Certificate has expired', 'sort_order' => 2],
            ['category' => 'certificate', 'code' => 'revoked', 'label' => 'Revoked', 'description' => 'Certificate revoked', 'sort_order' => 3],

            // Payment statuses
            ['category' => 'payment', 'code' => 'pending', 'label' => 'Pending', 'description' => 'Payment pending', 'sort_order' => 1],
            ['category' => 'payment', 'code' => 'paid', 'label' => 'Paid', 'description' => 'Payment completed', 'sort_order' => 2],
            ['category' => 'payment', 'code' => 'cancelled', 'label' => 'Cancelled', 'description' => 'Payment cancelled', 'sort_order' => 3],
            ['category' => 'payment', 'code' => 'refunded', 'label' => 'Refunded', 'description' => 'Payment refunded', 'sort_order' => 4],

            // Event statuses
            ['category' => 'event', 'code' => 'draft', 'label' => 'Draft', 'description' => 'Event is being planned', 'sort_order' => 1],
            ['category' => 'event', 'code' => 'published', 'label' => 'Published', 'description' => 'Event published', 'sort_order' => 2],
            ['category' => 'event', 'code' => 'ongoing', 'label' => 'Ongoing', 'description' => 'Event is ongoing', 'sort_order' => 3],
            ['category' => 'event', 'code' => 'completed', 'label' => 'Completed', 'description' => 'Event completed', 'sort_order' => 4],
            ['category' => 'event', 'code' => 'cancelled', 'label' => 'Cancelled', 'description' => 'Event cancelled', 'sort_order' => 5],

            // Document verification statuses
            ['category' => 'document', 'code' => 'pending', 'label' => 'Pending', 'description' => 'Pending verification', 'sort_order' => 1],
            ['category' => 'document', 'code' => 'verified', 'label' => 'Verified', 'description' => 'Document verified', 'sort_order' => 2],
            ['category' => 'document', 'code' => 'rejected', 'label' => 'Rejected', 'description' => 'Document rejected', 'sort_order' => 3],
        ];

        foreach ($statuses as $status) {
            MasterStatus::create($status);
        }
    }

    private function seedMethods(): void
    {
        $methods = [
            // Assessment methods
            ['category' => 'assessment', 'code' => 'observation', 'name' => 'Observasi', 'description' => 'Assessment through observation'],
            ['category' => 'assessment', 'code' => 'interview', 'name' => 'Wawancara', 'description' => 'Assessment through interview'],
            ['category' => 'assessment', 'code' => 'portfolio', 'name' => 'Portofolio', 'description' => 'Assessment through portfolio review'],
            ['category' => 'assessment', 'code' => 'test', 'name' => 'Tes Tertulis', 'description' => 'Written test'],
            ['category' => 'assessment', 'code' => 'demonstration', 'name' => 'Demonstrasi', 'description' => 'Practical demonstration'],

            // Evidence types
            ['category' => 'evidence', 'code' => 'direct', 'name' => 'Bukti Langsung', 'description' => 'Direct evidence'],
            ['category' => 'evidence', 'code' => 'indirect', 'name' => 'Bukti Tidak Langsung', 'description' => 'Indirect evidence'],
            ['category' => 'evidence', 'code' => 'supplementary', 'name' => 'Bukti Tambahan', 'description' => 'Supplementary evidence'],

            // Payment methods
            ['category' => 'payment', 'code' => 'bank-transfer', 'name' => 'Transfer Bank', 'description' => 'Bank transfer'],
            ['category' => 'payment', 'code' => 'e-wallet', 'name' => 'E-Wallet', 'description' => 'Electronic wallet (GoPay, OVO, Dana, etc)'],
            ['category' => 'payment', 'code' => 'virtual-account', 'name' => 'Virtual Account', 'description' => 'Virtual account'],
            ['category' => 'payment', 'code' => 'credit-card', 'name' => 'Kartu Kredit', 'description' => 'Credit card payment'],
        ];

        foreach ($methods as $method) {
            MasterMethod::create($method);
        }
    }

    private function seedDocumentTypes(): void
    {
        $documentTypes = [
            ['code' => 'ktp', 'name' => 'KTP', 'description' => 'Kartu Tanda Penduduk', 'retention_months' => 60],
            ['code' => 'ijazah', 'name' => 'Ijazah', 'description' => 'Certificate of education', 'retention_months' => null],
            ['code' => 'cv', 'name' => 'CV / Resume', 'description' => 'Curriculum Vitae', 'retention_months' => 36],
            ['code' => 'photo', 'name' => 'Pas Foto', 'description' => 'Passport photo', 'retention_months' => 24],
            ['code' => 'sertifikat', 'name' => 'Sertifikat', 'description' => 'Certificate documents', 'retention_months' => null],
            ['code' => 'surat-pernyataan', 'name' => 'Surat Pernyataan', 'description' => 'Statement letter', 'retention_months' => 60],
            ['code' => 'bukti-kerja', 'name' => 'Bukti Pengalaman Kerja', 'description' => 'Work experience proof', 'retention_months' => 36],
            ['code' => 'portofolio', 'name' => 'Dokumen Portofolio', 'description' => 'Portfolio documents', 'retention_months' => 24],
            ['code' => 'apl01', 'name' => 'Form APL-01', 'description' => 'Application form APL-01', 'retention_months' => 60],
            ['code' => 'apl02', 'name' => 'Form APL-02', 'description' => 'Portfolio form APL-02', 'retention_months' => 60],
            ['code' => 'mak01', 'name' => 'Form MAK-01', 'description' => 'Assessment checklist', 'retention_months' => 60],
            ['code' => 'berita-acara', 'name' => 'Berita Acara', 'description' => 'Official minutes', 'retention_months' => 60],
        ];

        foreach ($documentTypes as $docType) {
            MasterDocumentType::create($docType);
        }
    }
}
