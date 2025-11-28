<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Announcement;
use App\Models\Scheme;
use App\Models\Event;
use App\Models\Assessor;
use App\Models\Assessee;
use App\Models\Tuk;
use App\Models\Apl01Form;
use App\Models\Apl01Review;
use App\Models\Apl02Unit;
use App\Models\Apl02AssessorReview;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Certificate;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check user role
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);
        $isAssessor = $user->hasRole('assessor');
        $isAssessee = $user->hasRole('assessee');

        // Common stats for all users
        $stats = $this->getCommonStats();

        // Role-specific data
        if ($isAdmin) {
            $roleData = $this->getAdminDashboardData();
        } elseif ($isAssessor) {
            $roleData = $this->getAssessorDashboardData($user);
        } elseif ($isAssessee) {
            $roleData = $this->getAssesseeDashboardData($user);
        } else {
            $roleData = [];
        }

        // Merge all data
        $data = array_merge(
            ['stats' => $stats],
            $roleData,
            ['user_role' => $isAdmin ? 'admin' : ($isAssessor ? 'assessor' : ($isAssessee ? 'assessee' : 'user'))]
        );

        return view('admin.dashboard', $data);
    }

    private function getCommonStats(): array
    {
        return [
            // Content
            'total_news' => News::count(),
            'published_news' => News::published()->count(),
            'draft_news' => News::where('is_published', false)->count(),
            'total_announcements' => Announcement::count(),
            'published_announcements' => Announcement::published()->count(),
            'draft_announcements' => Announcement::where('is_published', false)->count(),
        ];
    }

    private function getAdminDashboardData(): array
    {
        // Master Data Statistics
        $masterDataStats = [
            'total_schemes' => Scheme::count(),
            'active_schemes' => Scheme::where('is_active', true)->count(),
            'total_events' => Event::count(),
            'active_events' => Event::where('is_active', true)->count(),
            'upcoming_events' => Event::where('is_active', true)->where('start_date', '>', now())->count(),
            'total_assessors' => Assessor::count(),
            'active_assessors' => Assessor::where('is_active', true)->count(),
            'total_assessees' => Assessee::count(),
            'total_tuk' => Tuk::count(),
            'active_tuk' => Tuk::where('is_active', true)->count(),
            'total_users' => User::count(),
        ];

        // Certification Flow Statistics
        $certificationStats = [
            // APL-01
            'total_apl01' => Apl01Form::count(),
            'apl01_pending' => Apl01Form::where('status', 'submitted')->count(),
            'apl01_approved' => Apl01Form::where('status', 'approved')->count(),
            'apl01_in_review' => Apl01Form::where('status', 'in_review')->count(),

            // APL-01 Reviews (uses decision column, not status)
            'total_apl01_reviews' => Apl01Review::count(),
            'apl01_reviews_pending' => Apl01Review::where('decision', 'pending')->count(),

            // APL-02
            'total_apl02_units' => Apl02Unit::count(),
            'apl02_in_progress' => Apl02Unit::where('status', 'in_progress')->count(),
            'apl02_completed' => Apl02Unit::whereIn('status', ['completed', 'competent'])->count(),

            // APL-02 Reviews
            'total_apl02_reviews' => Apl02AssessorReview::count(),
            'apl02_reviews_pending' => Apl02AssessorReview::whereIn('status', ['draft', 'in_progress'])->count(),

            // Assessments
            'total_assessments' => Assessment::count(),
            'assessments_scheduled' => Assessment::where('status', 'scheduled')->count(),
            'assessments_in_progress' => Assessment::where('status', 'in_progress')->count(),
            'assessments_completed' => Assessment::where('status', 'completed')->count(),

            // Results (uses approval_status column)
            'total_results' => AssessmentResult::count(),
            'results_pending_approval' => AssessmentResult::where('approval_status', 'pending')->count(),
            'results_competent' => AssessmentResult::where('final_result', 'competent')->count(),
            'results_not_competent' => AssessmentResult::where('final_result', 'not_yet_competent')->count(),

            // Certificates
            'total_certificates' => Certificate::count(),
            'certificates_active' => Certificate::where('status', 'active')->count(),
            'certificates_pending' => Certificate::whereIn('status', ['pending', 'processing'])->count(),
            'certificates_expiring_soon' => Certificate::where('status', 'active')
                ->where('valid_until', '<=', now()->addMonths(3))
                ->where('valid_until', '>', now())
                ->count(),
        ];

        // Payment Statistics
        $paymentStats = [
            'total_payments' => Payment::count(),
            'payments_pending' => Payment::where('status', 'pending')->count(),
            'payments_completed' => Payment::where('status', 'paid')->count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('total_amount') ?? 0,
            'pending_revenue' => Payment::where('status', 'pending')->sum('total_amount') ?? 0,
        ];

        // Recent items
        $recentNews = News::latest()->take(5)->get();
        $recentAnnouncements = Announcement::latest()->take(5)->get();
        $recentApl01 = Apl01Form::with(['assessee', 'scheme'])->latest()->take(5)->get();
        $recentAssessments = Assessment::with(['assessee', 'scheme'])->latest()->take(5)->get();
        $recentCertificates = Certificate::with(['assessee', 'scheme'])->latest()->take(5)->get();

        // Pending Actions for Admin
        $pendingActions = [
            'apl01_to_review' => Apl01Form::where('status', 'submitted')->count(),
            'apl02_to_review' => Apl02Unit::where('status', 'submitted')->count(),
            'results_to_approve' => AssessmentResult::where('approval_status', 'pending')->count(),
            'certificates_to_issue' => Certificate::where('status', 'pending')->count(),
            'payments_to_verify' => Payment::where('status', 'pending_verification')->count(),
        ];

        // Upcoming Events
        $upcomingEvents = Event::with(['scheme'])
            ->where('is_active', true)
            ->orderBy('start_date')
            ->take(5)
            ->get();

        return [
            'master_data_stats' => $masterDataStats,
            'certification_stats' => $certificationStats,
            'payment_stats' => $paymentStats,
            'recent_news' => $recentNews,
            'recent_announcements' => $recentAnnouncements,
            'recent_apl01' => $recentApl01,
            'recent_assessments' => $recentAssessments,
            'recent_certificates' => $recentCertificates,
            'pending_actions' => $pendingActions,
            'upcoming_events' => $upcomingEvents,
        ];
    }

    private function getAssessorDashboardData($user): array
    {
        $assessor = Assessor::where('user_id', $user->id)->first();

        if (!$assessor) {
            return [
                'review_stats' => [
                    'total_apl01_reviews' => 0,
                    'pending_apl01_reviews' => 0,
                    'completed_apl01_reviews' => 0,
                    'total_apl02_reviews' => 0,
                    'pending_apl02_reviews' => 0,
                    'completed_apl02_reviews' => 0,
                    'total_assessments' => 0,
                    'scheduled_assessments' => 0,
                    'in_progress_assessments' => 0,
                ],
                'pending_apl01_reviews' => collect(),
                'pending_apl02_reviews' => collect(),
                'upcoming_assessments' => collect(),
                'recent_news' => News::latest()->take(3)->get(),
                'recent_announcements' => Announcement::latest()->take(3)->get(),
            ];
        }

        // Assessor's review statistics
        // Note: apl01_review.reviewer_id stores User ID, not Assessor ID
        $reviewStats = [
            'total_apl01_reviews' => Apl01Review::where('reviewer_id', $user->id)->count(),
            'pending_apl01_reviews' => Apl01Review::where('reviewer_id', $user->id)
                ->where('decision', 'pending')->count(),
            'completed_apl01_reviews' => Apl01Review::where('reviewer_id', $user->id)
                ->whereNotNull('completed_at')->count(),

            'total_apl02_reviews' => Apl02AssessorReview::where('assessor_id', $user->id)->count(),
            'pending_apl02_reviews' => Apl02AssessorReview::where('assessor_id', $user->id)
                ->whereIn('status', ['draft', 'in_progress'])->count(),
            'completed_apl02_reviews' => Apl02AssessorReview::where('assessor_id', $user->id)
                ->where('status', 'completed')->count(),

            'total_assessments' => Assessment::where('lead_assessor_id', $assessor->id)->count(),
            'scheduled_assessments' => Assessment::where('lead_assessor_id', $assessor->id)
                ->where('status', 'scheduled')->count(),
            'in_progress_assessments' => Assessment::where('lead_assessor_id', $assessor->id)
                ->where('status', 'in_progress')->count(),
        ];

        // My pending reviews
        // Note: Apl01Review model uses 'form' relationship, not 'apl01Form'
        $pendingApl01Reviews = Apl01Review::with(['form.assessee', 'form.scheme'])
            ->where('reviewer_id', $user->id)
            ->where('decision', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingApl02Reviews = Apl02AssessorReview::with(['apl02Unit.assessee'])
            ->where('assessor_id', $user->id)
            ->whereIn('status', ['draft', 'in_progress'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming assessments
        $upcomingAssessments = Assessment::with(['assessee', 'scheme', 'tuk'])
            ->where('lead_assessor_id', $assessor->id)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        return [
            'review_stats' => $reviewStats,
            'pending_apl01_reviews' => $pendingApl01Reviews,
            'pending_apl02_reviews' => $pendingApl02Reviews,
            'upcoming_assessments' => $upcomingAssessments,
            'recent_news' => News::latest()->take(3)->get(),
            'recent_announcements' => Announcement::latest()->take(3)->get(),
        ];
    }

    private function getAssesseeDashboardData($user): array
    {
        $assessee = Assessee::where('user_id', $user->id)->first();

        if (!$assessee) {
            return [
                'my_stats' => [
                    'total_apl01' => 0,
                    'apl01_approved' => 0,
                    'apl01_in_review' => 0,
                    'total_apl02_units' => 0,
                    'apl02_completed' => 0,
                    'total_assessments' => 0,
                    'assessments_scheduled' => 0,
                    'total_certificates' => 0,
                    'active_certificates' => 0,
                ],
                'my_apl01_forms' => collect(),
                'my_upcoming_assessments' => collect(),
                'my_certificates' => collect(),
                'available_events' => collect(),
                'pending_payments' => collect(),
                'recent_news' => News::latest()->take(3)->get(),
                'recent_announcements' => Announcement::latest()->take(3)->get(),
            ];
        }

        // My certification progress
        $myStats = [
            'total_apl01' => Apl01Form::where('assessee_id', $assessee->id)->count(),
            'apl01_approved' => Apl01Form::where('assessee_id', $assessee->id)->where('status', 'approved')->count(),
            'apl01_in_review' => Apl01Form::where('assessee_id', $assessee->id)->whereIn('status', ['submitted', 'in_review'])->count(),

            'total_apl02_units' => Apl02Unit::where('assessee_id', $assessee->id)->count(),
            'apl02_completed' => Apl02Unit::where('assessee_id', $assessee->id)->whereIn('status', ['completed', 'competent'])->count(),

            'total_assessments' => Assessment::where('assessee_id', $assessee->id)->count(),
            'assessments_scheduled' => Assessment::where('assessee_id', $assessee->id)->where('status', 'scheduled')->count(),

            'total_certificates' => Certificate::where('assessee_id', $assessee->id)->count(),
            'active_certificates' => Certificate::where('assessee_id', $assessee->id)->where('status', 'active')->count(),
        ];

        // My recent APL-01 submissions
        $myApl01Forms = Apl01Form::with(['scheme', 'event'])
            ->where('assessee_id', $assessee->id)
            ->latest()
            ->take(5)
            ->get();

        // My upcoming assessments
        $myUpcomingAssessments = Assessment::with(['scheme', 'tuk'])
            ->where('assessee_id', $assessee->id)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        // My certificates
        $myCertificates = Certificate::with(['scheme'])
            ->where('assessee_id', $assessee->id)
            ->latest()
            ->take(5)
            ->get();

        // Available events
        $availableEvents = Event::with(['scheme'])
            ->where('is_active', true)
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // My payments - use payer_id since there's no assessee_id
        $pendingPayments = Payment::where('payer_id', $assessee->id)
            ->where('payer_type', 'assessee')
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get();

        return [
            'my_stats' => $myStats,
            'my_apl01_forms' => $myApl01Forms,
            'my_upcoming_assessments' => $myUpcomingAssessments,
            'my_certificates' => $myCertificates,
            'available_events' => $availableEvents,
            'pending_payments' => $pendingPayments,
            'recent_news' => News::latest()->take(3)->get(),
            'recent_announcements' => Announcement::latest()->take(3)->get(),
        ];
    }
}
