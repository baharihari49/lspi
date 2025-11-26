<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apl01Form;
use App\Models\Apl02Unit;
use App\Models\Assessment;
use App\Models\Certificate;
use App\Models\Scheme;
use App\Models\Tuk;
use App\Models\Assessor;
use App\Models\Assessee;
use App\Models\Event;
use App\Models\User;
use App\Models\Payment;
use App\Models\News;
use App\Models\Announcement;
use App\Models\Apl01Review;
use App\Models\Apl02AssessorReview;

class ModulesController extends Controller
{
    public function index()
    {
        // Gather statistics for each module
        $stats = [
            // Sertifikasi
            'apl01' => Apl01Form::count(),
            'apl01_pending' => Apl01Form::where('status', 'submitted')->count(),
            'apl02_units' => Apl02Unit::count(),
            'apl02_pending' => Apl02Unit::where('status', 'submitted')->count(),
            'assessments' => Assessment::count(),
            'assessments_pending' => Assessment::where('status', 'scheduled')->count(),
            'certificates' => Certificate::count(),
            'certificates_active' => Certificate::where('status', 'active')->count(),

            // Master Data
            'schemes' => Scheme::count(),
            'tuk' => Tuk::count(),
            'assessors' => Assessor::count(),
            'assessees' => Assessee::count(),

            // Events & Users
            'events' => Event::count(),
            'events_active' => Event::where('is_published', true)->count(),
            'users' => User::count(),
            'payments' => Payment::count(),
            'payments_pending' => Payment::where('status', 'pending')->count(),

            // Content
            'news' => News::count(),
            'announcements' => Announcement::count(),

            // Reviews
            'apl01_reviews' => Apl01Review::count(),
            'apl01_reviews_pending' => Apl01Review::where('decision', 'pending')->count(),
            'apl02_reviews' => Apl02AssessorReview::count(),
            'apl02_reviews_pending' => Apl02AssessorReview::where('decision', 'pending')->count(),
        ];

        // Define module groups
        $moduleGroups = [
            [
                'title' => 'Sertifikasi',
                'description' => 'Modul utama proses sertifikasi',
                'color' => 'blue',
                'modules' => [
                    [
                        'name' => 'APL-01 Forms',
                        'description' => 'Formulir permohonan sertifikasi',
                        'icon' => 'description',
                        'route' => 'admin.apl01.index',
                        'color' => 'blue',
                        'stats' => $stats['apl01'],
                        'badge' => $stats['apl01_pending'] > 0 ? $stats['apl01_pending'] . ' pending' : null,
                        'badge_color' => 'yellow',
                    ],
                    [
                        'name' => 'APL-02 Portfolio',
                        'description' => 'Portfolio bukti kompetensi',
                        'icon' => 'folder_open',
                        'route' => 'admin.apl02.units.index',
                        'color' => 'indigo',
                        'stats' => $stats['apl02_units'],
                        'badge' => $stats['apl02_pending'] > 0 ? $stats['apl02_pending'] . ' pending' : null,
                        'badge_color' => 'yellow',
                    ],
                    [
                        'name' => 'Assessment',
                        'description' => 'Pelaksanaan asesmen',
                        'icon' => 'assignment',
                        'route' => 'admin.assessments.index',
                        'color' => 'purple',
                        'stats' => $stats['assessments'],
                        'badge' => $stats['assessments_pending'] > 0 ? $stats['assessments_pending'] . ' scheduled' : null,
                        'badge_color' => 'blue',
                    ],
                    [
                        'name' => 'Certificates',
                        'description' => 'Manajemen sertifikat',
                        'icon' => 'workspace_premium',
                        'route' => 'admin.certificates.index',
                        'color' => 'green',
                        'stats' => $stats['certificates'],
                        'badge' => $stats['certificates_active'] > 0 ? $stats['certificates_active'] . ' active' : null,
                        'badge_color' => 'green',
                    ],
                    [
                        'name' => 'Certification Flow',
                        'description' => 'Dashboard alur sertifikasi',
                        'icon' => 'conversion_path',
                        'route' => 'admin.certification-flow.dashboard',
                        'color' => 'cyan',
                        'stats' => null,
                        'badge' => null,
                    ],
                ],
            ],
            [
                'title' => 'Review & Approval',
                'description' => 'Review dan persetujuan dokumen',
                'color' => 'orange',
                'modules' => [
                    [
                        'name' => 'APL-01 Reviews',
                        'description' => 'Review formulir APL-01',
                        'icon' => 'rate_review',
                        'route' => 'admin.apl01-reviews.index',
                        'color' => 'orange',
                        'stats' => $stats['apl01_reviews'],
                        'badge' => $stats['apl01_reviews_pending'] > 0 ? $stats['apl01_reviews_pending'] . ' pending' : null,
                        'badge_color' => 'red',
                    ],
                    [
                        'name' => 'APL-02 Reviews',
                        'description' => 'Review portfolio APL-02',
                        'icon' => 'fact_check',
                        'route' => 'admin.apl02.reviews.index',
                        'color' => 'amber',
                        'stats' => $stats['apl02_reviews'],
                        'badge' => $stats['apl02_reviews_pending'] > 0 ? $stats['apl02_reviews_pending'] . ' pending' : null,
                        'badge_color' => 'red',
                    ],
                    [
                        'name' => 'Result Approval',
                        'description' => 'Persetujuan hasil asesmen',
                        'icon' => 'task_alt',
                        'route' => 'admin.result-approval.index',
                        'color' => 'rose',
                        'stats' => null,
                        'badge' => null,
                    ],
                ],
            ],
            [
                'title' => 'Master Data',
                'description' => 'Data master aplikasi',
                'color' => 'emerald',
                'modules' => [
                    [
                        'name' => 'Schemes',
                        'description' => 'Skema sertifikasi',
                        'icon' => 'verified',
                        'route' => 'admin.schemes.index',
                        'color' => 'emerald',
                        'stats' => $stats['schemes'],
                        'badge' => null,
                    ],
                    [
                        'name' => 'TUK',
                        'description' => 'Tempat Uji Kompetensi',
                        'icon' => 'location_city',
                        'route' => 'admin.tuk.index',
                        'color' => 'teal',
                        'stats' => $stats['tuk'],
                        'badge' => null,
                    ],
                    [
                        'name' => 'Assessors',
                        'description' => 'Data asesor',
                        'icon' => 'badge',
                        'route' => 'admin.assessors.index',
                        'color' => 'sky',
                        'stats' => $stats['assessors'],
                        'badge' => null,
                    ],
                    [
                        'name' => 'Assessees',
                        'description' => 'Data peserta sertifikasi',
                        'icon' => 'school',
                        'route' => 'admin.assessees.index',
                        'color' => 'violet',
                        'stats' => $stats['assessees'],
                        'badge' => null,
                    ],
                ],
            ],
            [
                'title' => 'Events & Users',
                'description' => 'Manajemen event dan pengguna',
                'color' => 'pink',
                'modules' => [
                    [
                        'name' => 'Events',
                        'description' => 'Event sertifikasi',
                        'icon' => 'event',
                        'route' => 'admin.events.index',
                        'color' => 'pink',
                        'stats' => $stats['events'],
                        'badge' => $stats['events_active'] > 0 ? $stats['events_active'] . ' active' : null,
                        'badge_color' => 'green',
                    ],
                    [
                        'name' => 'Users',
                        'description' => 'Manajemen pengguna',
                        'icon' => 'people',
                        'route' => 'admin.users.index',
                        'color' => 'slate',
                        'stats' => $stats['users'],
                        'badge' => null,
                    ],
                    [
                        'name' => 'Payments',
                        'description' => 'Manajemen pembayaran',
                        'icon' => 'payments',
                        'route' => 'admin.payments.index',
                        'color' => 'lime',
                        'stats' => $stats['payments'],
                        'badge' => $stats['payments_pending'] > 0 ? $stats['payments_pending'] . ' pending' : null,
                        'badge_color' => 'yellow',
                    ],
                ],
            ],
            [
                'title' => 'Content',
                'description' => 'Manajemen konten website',
                'color' => 'fuchsia',
                'modules' => [
                    [
                        'name' => 'News',
                        'description' => 'Berita dan artikel',
                        'icon' => 'article',
                        'route' => 'admin.news.index',
                        'color' => 'fuchsia',
                        'stats' => $stats['news'],
                        'badge' => null,
                    ],
                    [
                        'name' => 'Announcements',
                        'description' => 'Pengumuman resmi',
                        'icon' => 'campaign',
                        'route' => 'admin.announcements.index',
                        'color' => 'red',
                        'stats' => $stats['announcements'],
                        'badge' => null,
                    ],
                ],
            ],
            [
                'title' => 'Settings',
                'description' => 'Pengaturan aplikasi',
                'color' => 'gray',
                'modules' => [
                    [
                        'name' => 'LSP Profile',
                        'description' => 'Profil lembaga',
                        'icon' => 'corporate_fare',
                        'route' => 'admin.lsp-profiles.index',
                        'color' => 'gray',
                        'stats' => null,
                        'badge' => null,
                    ],
                    [
                        'name' => 'Organization',
                        'description' => 'Pengaturan organisasi',
                        'icon' => 'settings',
                        'route' => 'admin.org-settings.index',
                        'color' => 'zinc',
                        'stats' => null,
                        'badge' => null,
                    ],
                    [
                        'name' => 'Roles & Permissions',
                        'description' => 'Hak akses pengguna',
                        'icon' => 'shield',
                        'route' => 'admin.master-roles.index',
                        'color' => 'stone',
                        'stats' => null,
                        'badge' => null,
                    ],
                ],
            ],
        ];

        return view('admin.modules.index', compact('stats', 'moduleGroups'));
    }
}
