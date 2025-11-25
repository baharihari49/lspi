<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/struktur-organisasi', function () {
    $positions = App\Models\OrganizationalPosition::active()->with('children')->rootLevel()->get();
    return view('struktur-organisasi', compact('positions'));
});

Route::get('/skema', function () {
    return view('skema');
});

Route::get('/skema/penerapan-it-artikel-ilmiah', function () {
    return view('skema.penerapan-it-artikel-ilmiah');
});

Route::get('/skema/pengelolaan-jurnal-elektronik', function () {
    return view('skema.pengelolaan-jurnal-elektronik');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/news', function () {
    $news = App\Models\News::published()
        ->latest('published_at')
        ->paginate(6);
    return view('news', compact('news'));
});

Route::get('/news/{news:slug}', function (App\Models\News $news) {
    // Get other recent published news for "Recent Articles" section
    $recentNews = App\Models\News::published()
        ->where('id', '!=', $news->id)
        ->latest('published_at')
        ->take(3)
        ->get();

    return view('news-detail', compact('news', 'recentNews'));
});

Route::get('/pengumuman', function () {
    return view('announcements');
});

// Authentication Routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Admin Routes (protected by auth middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // Master Data Management
    Route::resource('master-roles', App\Http\Controllers\Admin\MasterRoleController::class);
    Route::resource('master-permissions', App\Http\Controllers\Admin\MasterPermissionController::class);
    Route::resource('master-statuses', App\Http\Controllers\Admin\MasterStatusController::class);
    Route::resource('master-methods', App\Http\Controllers\Admin\MasterMethodController::class);
    Route::resource('master-document-types', App\Http\Controllers\Admin\MasterDocumentTypeController::class);

    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);

    // LSP Configuration
    Route::resource('lsp-profiles', App\Http\Controllers\Admin\LspProfileController::class);
    Route::resource('org-settings', App\Http\Controllers\Admin\OrgSettingController::class);

    // TUK Management
    Route::resource('tuk', App\Http\Controllers\Admin\TukController::class);
    Route::resource('tuk-facilities', App\Http\Controllers\Admin\TukFacilityController::class);
    Route::resource('tuk-documents', App\Http\Controllers\Admin\TukDocumentController::class);
    Route::resource('tuk-schedules', App\Http\Controllers\Admin\TukScheduleController::class);

    // News Management
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class);

    // Announcements Management
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);

    // Organizational Structure Management
    Route::resource('organizational-structure', App\Http\Controllers\Admin\OrganizationalStructureController::class);

    // Assessor Management
    Route::resource('assessors', App\Http\Controllers\Admin\AssessorController::class);
    Route::post('assessors/{assessor}/verify', [App\Http\Controllers\Admin\AssessorController::class, 'verify'])->name('assessors.verify');

    Route::resource('assessor-documents', App\Http\Controllers\Admin\AssessorDocumentController::class);
    Route::post('assessor-documents/{assessorDocument}/verify', [App\Http\Controllers\Admin\AssessorDocumentController::class, 'verify'])->name('assessor-documents.verify');

    Route::resource('assessor-competency-scopes', App\Http\Controllers\Admin\AssessorCompetencyScopeController::class);
    Route::post('assessor-competency-scopes/{assessorCompetencyScope}/approve', [App\Http\Controllers\Admin\AssessorCompetencyScopeController::class, 'approve'])->name('assessor-competency-scopes.approve');

    Route::resource('assessor-experiences', App\Http\Controllers\Admin\AssessorExperienceController::class);

    Route::resource('assessor-bank-info', App\Http\Controllers\Admin\AssessorBankInfoController::class);
    Route::post('assessor-bank-info/{assessorBankInfo}/verify', [App\Http\Controllers\Admin\AssessorBankInfoController::class, 'verify'])->name('assessor-bank-info.verify');

    // Certification Scheme Management
    Route::resource('schemes', App\Http\Controllers\Admin\SchemeController::class);

    // Scheme Versions (nested under schemes)
    Route::prefix('schemes/{scheme}')->name('schemes.')->group(function () {
        Route::resource('versions', App\Http\Controllers\Admin\SchemeVersionController::class);
        Route::post('versions/{version}/set-current', [App\Http\Controllers\Admin\SchemeVersionController::class, 'setCurrent'])->name('versions.set-current');
        Route::post('versions/{version}/approve', [App\Http\Controllers\Admin\SchemeVersionController::class, 'approve'])->name('versions.approve');

        // Scheme Units (nested under versions)
        Route::prefix('versions/{version}')->name('versions.')->group(function () {
            Route::resource('units', App\Http\Controllers\Admin\SchemeUnitController::class);

            // Scheme Elements (nested under units)
            Route::prefix('units/{unit}')->name('units.')->group(function () {
                Route::resource('elements', App\Http\Controllers\Admin\SchemeElementController::class);

                // Scheme Criteria (nested under elements)
                Route::prefix('elements/{element}')->name('elements.')->group(function () {
                    Route::resource('criteria', App\Http\Controllers\Admin\SchemeCriterionController::class);
                });
            });

            // Scheme Requirements (nested under versions)
            Route::resource('requirements', App\Http\Controllers\Admin\SchemeRequirementController::class);
        });
    });

    // Event Management
    Route::resource('events', App\Http\Controllers\Admin\EventController::class);
    Route::post('events/{event}/publish', [App\Http\Controllers\Admin\EventController::class, 'publish'])->name('events.publish');
    Route::post('events/{event}/unpublish', [App\Http\Controllers\Admin\EventController::class, 'unpublish'])->name('events.unpublish');

    // Event Sessions (nested under events)
    Route::prefix('events/{event}')->name('events.')->group(function () {
        Route::resource('sessions', App\Http\Controllers\Admin\EventSessionController::class);

        // Event TUK Assignments (nested under events)
        Route::resource('tuk', App\Http\Controllers\Admin\EventTukController::class);
        Route::post('tuk/{tuk}/confirm', [App\Http\Controllers\Admin\EventTukController::class, 'confirm'])->name('tuk.confirm');

        // Event Assessor Assignments (nested under events)
        Route::resource('assessors', App\Http\Controllers\Admin\EventAssessorController::class);
        Route::post('assessors/{assessor}/confirm', [App\Http\Controllers\Admin\EventAssessorController::class, 'confirm'])->name('assessors.confirm');
        Route::post('assessors/{assessor}/reject', [App\Http\Controllers\Admin\EventAssessorController::class, 'reject'])->name('assessors.reject');

        // Event Materials (nested under events)
        Route::resource('materials', App\Http\Controllers\Admin\EventMaterialController::class);
        Route::get('materials/{material}/download', [App\Http\Controllers\Admin\EventMaterialController::class, 'download'])->name('materials.download');

        // Event Attendance (nested under events)
        Route::resource('attendance', App\Http\Controllers\Admin\EventAttendanceController::class);
        Route::post('attendance/{attendance}/check-in', [App\Http\Controllers\Admin\EventAttendanceController::class, 'checkIn'])->name('attendance.check-in');
        Route::post('attendance/{attendance}/check-out', [App\Http\Controllers\Admin\EventAttendanceController::class, 'checkOut'])->name('attendance.check-out');
    });

    // Assessees (Peserta)
    Route::resource('assessees', App\Http\Controllers\Admin\AssesseeController::class);
    Route::post('assessees/{assessee}/verify', [App\Http\Controllers\Admin\AssesseeController::class, 'verify'])->name('assessees.verify');

    // Assessee nested resources
    Route::prefix('assessees/{assessee}')->name('assessees.')->group(function () {
        // Assessee Documents
        Route::resource('documents', App\Http\Controllers\Admin\AssesseeDocumentController::class);
        Route::get('documents/{document}/download', [App\Http\Controllers\Admin\AssesseeDocumentController::class, 'download'])->name('documents.download');
        Route::post('documents/{document}/verify', [App\Http\Controllers\Admin\AssesseeDocumentController::class, 'verify'])->name('documents.verify');

        // Assessee Employment Info
        Route::resource('employment', App\Http\Controllers\Admin\AssesseeEmploymentController::class);
        Route::post('employment/{employment}/verify', [App\Http\Controllers\Admin\AssesseeEmploymentController::class, 'verify'])->name('employment.verify');

        // Assessee Education History
        Route::resource('education', App\Http\Controllers\Admin\AssesseeEducationController::class);
        Route::post('education/{education}/verify', [App\Http\Controllers\Admin\AssesseeEducationController::class, 'verify'])->name('education.verify');

        // Assessee Experience
        Route::resource('experience', App\Http\Controllers\Admin\AssesseeExperienceController::class);
        Route::post('experience/{experience}/verify', [App\Http\Controllers\Admin\AssesseeExperienceController::class, 'verify'])->name('experience.verify');
    });

    // APL-01 Form Management
    Route::resource('apl01', App\Http\Controllers\Admin\Apl01FormController::class);
    Route::post('apl01/{apl01}/submit', [App\Http\Controllers\Admin\Apl01FormController::class, 'submit'])->name('apl01.submit');
    Route::post('apl01/{apl01}/declaration', [App\Http\Controllers\Admin\Apl01FormController::class, 'updateDeclaration'])->name('apl01.declaration');
    Route::post('apl01/autofill', [App\Http\Controllers\Admin\Apl01FormController::class, 'autofill'])->name('apl01.autofill');

    // APL-01 Form Fields (Form Builder)
    Route::resource('apl01-fields', App\Http\Controllers\Admin\Apl01FormFieldController::class)->parameters([
        'apl01-fields' => 'field'
    ]);
    Route::post('apl01-fields/reorder', [App\Http\Controllers\Admin\Apl01FormFieldController::class, 'reorder'])->name('apl01-fields.reorder');
    Route::post('apl01-fields/{field}/duplicate', [App\Http\Controllers\Admin\Apl01FormFieldController::class, 'duplicate'])->name('apl01-fields.duplicate');
    Route::get('schemes/{scheme}/fields', [App\Http\Controllers\Admin\Apl01FormFieldController::class, 'getByScheme'])->name('schemes.fields');

    // APL-01 Reviews
    Route::get('apl01-reviews/my-reviews', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'myReviews'])->name('apl01-reviews.my-reviews');
    Route::resource('apl01-reviews', App\Http\Controllers\Admin\Apl01ReviewController::class)->only(['index', 'show'])->parameters([
        'apl01-reviews' => 'review'
    ]);
    Route::get('apl01-reviews/{review}/review', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'review'])->name('apl01-reviews.review');
    Route::post('apl01-reviews/{review}/start', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'start'])->name('apl01-reviews.start');
    Route::post('apl01-reviews/{review}/submit-decision', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'submitDecision'])->name('apl01-reviews.submit-decision');
    Route::post('apl01-reviews/{review}/approve', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'approve'])->name('apl01-reviews.approve');
    Route::post('apl01-reviews/{review}/reject', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'reject'])->name('apl01-reviews.reject');
    Route::post('apl01-reviews/{review}/escalate', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'escalate'])->name('apl01-reviews.escalate');
    Route::post('apl01-reviews/{review}/reassign', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'reassign'])->name('apl01-reviews.reassign');
    Route::post('apl01-reviews/{review}/deadline', [App\Http\Controllers\Admin\Apl01ReviewController::class, 'updateDeadline'])->name('apl01-reviews.deadline');

    // APL-02 Units
    Route::resource('apl02/units', App\Http\Controllers\Admin\Apl02UnitController::class)
        ->names([
            'index' => 'apl02.units.index',
            'create' => 'apl02.units.create',
            'store' => 'apl02.units.store',
            'show' => 'apl02.units.show',
            'edit' => 'apl02.units.edit',
            'update' => 'apl02.units.update',
            'destroy' => 'apl02.units.destroy',
        ])
        ->parameters(['units' => 'unit']);
    Route::post('apl02/units/{unit}/submit', [App\Http\Controllers\Admin\Apl02UnitController::class, 'submit'])->name('apl02.units.submit');
    Route::post('apl02/units/{unit}/assign-assessor', [App\Http\Controllers\Admin\Apl02UnitController::class, 'assignAssessor'])->name('apl02.units.assign-assessor');
    Route::post('apl02/units/{unit}/start-assessment', [App\Http\Controllers\Admin\Apl02UnitController::class, 'startAssessment'])->name('apl02.units.start-assessment');
    Route::post('apl02/units/{unit}/complete-assessment', [App\Http\Controllers\Admin\Apl02UnitController::class, 'completeAssessment'])->name('apl02.units.complete-assessment');
    Route::post('apl02/units/{unit}/lock', [App\Http\Controllers\Admin\Apl02UnitController::class, 'lock'])->name('apl02.units.lock');
    Route::post('apl02/units/{unit}/unlock', [App\Http\Controllers\Admin\Apl02UnitController::class, 'unlock'])->name('apl02.units.unlock');
    Route::post('apl02/units/{unit}/calculate-completion', [App\Http\Controllers\Admin\Apl02UnitController::class, 'calculateCompletion'])->name('apl02.units.calculate-completion');
    Route::get('apl02/api/scheme-units', [App\Http\Controllers\Admin\Apl02UnitController::class, 'getSchemeUnits'])->name('apl02.units.get-scheme-units');

    // APL-02 Evidence
    Route::resource('apl02/evidence', App\Http\Controllers\Admin\Apl02EvidenceController::class)
        ->names([
            'index' => 'apl02.evidence.index',
            'create' => 'apl02.evidence.create',
            'store' => 'apl02.evidence.store',
            'show' => 'apl02.evidence.show',
            'edit' => 'apl02.evidence.edit',
            'update' => 'apl02.evidence.update',
            'destroy' => 'apl02.evidence.destroy',
        ])
        ->parameters(['evidence' => 'evidence']);
    Route::post('apl02/evidence/{evidence}/verify', [App\Http\Controllers\Admin\Apl02EvidenceController::class, 'verify'])->name('apl02.evidence.verify');
    Route::post('apl02/evidence/{evidence}/reject', [App\Http\Controllers\Admin\Apl02EvidenceController::class, 'reject'])->name('apl02.evidence.reject');
    Route::post('apl02/evidence/{evidence}/assess', [App\Http\Controllers\Admin\Apl02EvidenceController::class, 'assess'])->name('apl02.evidence.assess');
    Route::post('apl02/evidence/{evidence}/map-to-element', [App\Http\Controllers\Admin\Apl02EvidenceController::class, 'mapToElement'])->name('apl02.evidence.map-to-element');
    Route::delete('apl02/evidence/{evidence}/unmap/{map}', [App\Http\Controllers\Admin\Apl02EvidenceController::class, 'unmapFromElement'])->name('apl02.evidence.unmap-from-element');
    Route::get('apl02/evidence/{evidence}/download', [App\Http\Controllers\Admin\Apl02EvidenceController::class, 'download'])->name('apl02.evidence.download');

    // APL-02 Assessor Reviews
    Route::get('apl02/reviews/my-reviews', [App\Http\Controllers\Admin\Apl02AssessorReviewController::class, 'myReviews'])->name('apl02.reviews.my-reviews');
    Route::resource('apl02/reviews', App\Http\Controllers\Admin\Apl02AssessorReviewController::class)
        ->names([
            'index' => 'apl02.reviews.index',
            'create' => 'apl02.reviews.create',
            'store' => 'apl02.reviews.store',
            'show' => 'apl02.reviews.show',
            'edit' => 'apl02.reviews.edit',
            'update' => 'apl02.reviews.update',
            'destroy' => 'apl02.reviews.destroy',
        ])
        ->parameters(['reviews' => 'review']);
    Route::get('apl02/reviews/{review}/conduct', [App\Http\Controllers\Admin\Apl02AssessorReviewController::class, 'conduct'])->name('apl02.reviews.conduct');
    Route::post('apl02/reviews/{review}/submit-review', [App\Http\Controllers\Admin\Apl02AssessorReviewController::class, 'submitReview'])->name('apl02.reviews.submit-review');
    Route::post('apl02/reviews/{review}/verify', [App\Http\Controllers\Admin\Apl02AssessorReviewController::class, 'verify'])->name('apl02.reviews.verify');
    Route::post('apl02/reviews/{review}/require-revision', [App\Http\Controllers\Admin\Apl02AssessorReviewController::class, 'requireRevision'])->name('apl02.reviews.require-revision');
    Route::post('apl02/reviews/{review}/mark-as-final', [App\Http\Controllers\Admin\Apl02AssessorReviewController::class, 'markAsFinal'])->name('apl02.reviews.mark-as-final');
    Route::post('apl02/reviews/{review}/schedule-re-assessment', [App\Http\Controllers\Admin\Apl02AssessorReviewController::class, 'scheduleReAssessment'])->name('apl02.reviews.schedule-re-assessment');

    // Assessment Module Routes (Module K)
    Route::resource('assessments', App\Http\Controllers\Admin\AssessmentController::class)->names([
        'index' => 'assessments.index',
        'create' => 'assessments.create',
        'store' => 'assessments.store',
        'show' => 'assessments.show',
        'edit' => 'assessments.edit',
        'update' => 'assessments.update',
        'destroy' => 'assessments.destroy',
    ]);
    Route::post('assessments/{assessment}/update-status', [App\Http\Controllers\Admin\AssessmentController::class, 'updateStatus'])->name('assessments.update-status');
    Route::post('assessments/{assessment}/generate-units', [App\Http\Controllers\Admin\AssessmentController::class, 'generateUnits'])->name('assessments.generate-units');

    // Assessment Results Routes
    Route::resource('assessment-results', App\Http\Controllers\Admin\AssessmentResultController::class)->names([
        'index' => 'assessment-results.index',
        'create' => 'assessment-results.create',
        'store' => 'assessment-results.store',
        'show' => 'assessment-results.show',
        'edit' => 'assessment-results.edit',
        'update' => 'assessment-results.update',
        'destroy' => 'assessment-results.destroy',
    ])->parameters(['assessment-results' => 'assessmentResult']);
    Route::post('assessment-results/{assessmentResult}/publish', [App\Http\Controllers\Admin\AssessmentResultController::class, 'publish'])->name('assessment-results.publish');
    Route::post('assessment-results/{assessmentResult}/issue-certificate', [App\Http\Controllers\Admin\AssessmentResultController::class, 'issueCertificate'])->name('assessment-results.issue-certificate');

    // Result Approval Routes
    Route::resource('result-approval', App\Http\Controllers\Admin\ResultApprovalController::class)->parameters(['result-approval' => 'resultApproval']);
    Route::post('result-approval/{resultApproval}/process-decision', [App\Http\Controllers\Admin\ResultApprovalController::class, 'processDecision'])->name('result-approval.process-decision');
    Route::post('result-approval/{resultApproval}/delegate', [App\Http\Controllers\Admin\ResultApprovalController::class, 'delegate'])->name('result-approval.delegate');

    // Certificate Management Routes (Module L)
    Route::resource('certificates', App\Http\Controllers\Admin\CertificateController::class);
    Route::get('certificates/{certificate}/download', [App\Http\Controllers\Admin\CertificateController::class, 'download'])->name('certificates.download');
    Route::get('certificates/{certificate}/generate-qr', [App\Http\Controllers\Admin\CertificateController::class, 'generateQr'])->name('certificates.generate-qr');

    // Certificate Revoke Routes
    Route::resource('certificate-revoke', App\Http\Controllers\Admin\CertificateRevokeController::class)->parameters(['certificate-revoke' => 'certificateRevoke']);
    Route::post('certificate-revoke/{certificateRevoke}/approve', [App\Http\Controllers\Admin\CertificateRevokeController::class, 'approve'])->name('certificate-revoke.approve');
    Route::post('certificate-revoke/{certificateRevoke}/reject', [App\Http\Controllers\Admin\CertificateRevokeController::class, 'reject'])->name('certificate-revoke.reject');

    // Certificate Renewal Routes
    Route::resource('certificate-renewal', App\Http\Controllers\Admin\CertificateRenewalController::class)->parameters(['certificate-renewal' => 'certificateRenewal']);
    Route::post('certificate-renewal/{certificateRenewal}/approve', [App\Http\Controllers\Admin\CertificateRenewalController::class, 'approve'])->name('certificate-renewal.approve');
    Route::post('certificate-renewal/{certificateRenewal}/reject', [App\Http\Controllers\Admin\CertificateRenewalController::class, 'reject'])->name('certificate-renewal.reject');

    // Payment Management Routes (Module M)
    Route::resource('payments', App\Http\Controllers\Admin\PaymentController::class);
    Route::post('payments/{payment}/mark-paid', [App\Http\Controllers\Admin\PaymentController::class, 'markAsPaid'])->name('payments.mark-paid');
    Route::post('payments/{payment}/cancel', [App\Http\Controllers\Admin\PaymentController::class, 'cancel'])->name('payments.cancel');
    Route::post('payments/{payment}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');

    // Payment Methods Routes
    Route::resource('payment-methods', App\Http\Controllers\Admin\PaymentMethodController::class);
    Route::post('payment-methods/{paymentMethod}/toggle-status', [App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');

    // Assessment Units Routes
    Route::post('assessment-units/{assessmentUnit}/start', [App\Http\Controllers\Admin\AssessmentUnitController::class, 'start'])->name('assessment-units.start');
    Route::post('assessment-units/{assessmentUnit}/complete', [App\Http\Controllers\Admin\AssessmentUnitController::class, 'complete'])->name('assessment-units.complete');
    Route::resource('assessment-units', App\Http\Controllers\Admin\AssessmentUnitController::class)->names([
        'index' => 'assessment-units.index',
        'create' => 'assessment-units.create',
        'store' => 'assessment-units.store',
        'show' => 'assessment-units.show',
        'edit' => 'assessment-units.edit',
        'update' => 'assessment-units.update',
        'destroy' => 'assessment-units.destroy',
    ])->parameters(['assessment-units' => 'assessmentUnit']);

    // Assessment Criteria Routes
    Route::get('assessment-units/{assessmentUnit}/assess', [App\Http\Controllers\Admin\AssessmentCriteriaController::class, 'assessForm'])->name('assessment-criteria.assess-form');
    Route::post('assessment-criteria/{assessmentCriterion}/assess', [App\Http\Controllers\Admin\AssessmentCriteriaController::class, 'assess'])->name('assessment-criteria.assess');
    Route::post('assessment-criteria/bulk-assess', [App\Http\Controllers\Admin\AssessmentCriteriaController::class, 'bulkAssess'])->name('assessment-criteria.bulk-assess');
    Route::post('assessment-criteria/{assessmentCriterion}/toggle-critical', [App\Http\Controllers\Admin\AssessmentCriteriaController::class, 'toggleCritical'])->name('assessment-criteria.toggle-critical');
    Route::resource('assessment-criteria', App\Http\Controllers\Admin\AssessmentCriteriaController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);

    // Assessment Documents Routes
    Route::get('assessment-documents/get-units', [App\Http\Controllers\Admin\AssessmentDocumentController::class, 'getUnits'])->name('assessment-documents.get-units');
    Route::get('assessment-documents/get-criteria', [App\Http\Controllers\Admin\AssessmentDocumentController::class, 'getCriteria'])->name('assessment-documents.get-criteria');
    Route::post('assessment-documents/bulk-verify', [App\Http\Controllers\Admin\AssessmentDocumentController::class, 'bulkVerify'])->name('assessment-documents.bulk-verify');
    Route::post('assessment-documents/{assessmentDocument}/verify', [App\Http\Controllers\Admin\AssessmentDocumentController::class, 'verify'])->name('assessment-documents.verify');
    Route::get('assessment-documents/{assessmentDocument}/download', [App\Http\Controllers\Admin\AssessmentDocumentController::class, 'download'])->name('assessment-documents.download');
    Route::resource('assessment-documents', App\Http\Controllers\Admin\AssessmentDocumentController::class)->names([
        'index' => 'assessment-documents.index',
        'create' => 'assessment-documents.create',
        'store' => 'assessment-documents.store',
        'show' => 'assessment-documents.show',
        'edit' => 'assessment-documents.edit',
        'update' => 'assessment-documents.update',
        'destroy' => 'assessment-documents.destroy',
    ])->parameters(['assessment-documents' => 'assessmentDocument']);

    // Assessment Observations Routes
    Route::resource('assessment-observations', App\Http\Controllers\Admin\AssessmentObservationController::class)->parameters(['assessment-observations' => 'assessmentObservation']);

    // Assessment Interviews Routes
    Route::resource('assessment-interviews', App\Http\Controllers\Admin\AssessmentInterviewController::class)->parameters(['assessment-interviews' => 'assessmentInterview']);

    // Assessment Verification Routes
    Route::resource('assessment-verification', App\Http\Controllers\Admin\AssessmentVerificationController::class)->parameters(['assessment-verification' => 'assessmentVerification']);

    // =====================================================
    // ASSESSEE SELF-SERVICE ROUTES
    // =====================================================
    // Accessible by assessee role, or admin/super-admin for testing/support
    Route::middleware(['role:assessee,admin,super-admin'])->group(function () {
        // Available Events (for registration)
        Route::get('available-events', [App\Http\Controllers\Assessee\EventRegistrationController::class, 'index'])->name('available-events.index');
        Route::get('available-events/{event}', [App\Http\Controllers\Assessee\EventRegistrationController::class, 'show'])->name('available-events.show');
        Route::post('available-events/{event}/register', [App\Http\Controllers\Assessee\EventRegistrationController::class, 'register'])->name('available-events.register');

        // My Certificates
        Route::get('my-certificates', [App\Http\Controllers\Assessee\MyCertificateController::class, 'index'])->name('my-certificates.index');
        Route::get('my-certificates/{certificate}', [App\Http\Controllers\Assessee\MyCertificateController::class, 'show'])->name('my-certificates.show');
        Route::get('my-certificates/{certificate}/download', [App\Http\Controllers\Assessee\MyCertificateController::class, 'download'])->name('my-certificates.download');

        // My APL-01
        Route::get('my-apl01', [App\Http\Controllers\Assessee\MyApl01Controller::class, 'index'])->name('my-apl01.index');
        Route::get('my-apl01/{apl01}', [App\Http\Controllers\Assessee\MyApl01Controller::class, 'show'])->name('my-apl01.show');
        Route::get('my-apl01/{apl01}/edit', [App\Http\Controllers\Assessee\MyApl01Controller::class, 'edit'])->name('my-apl01.edit');
        Route::put('my-apl01/{apl01}', [App\Http\Controllers\Assessee\MyApl01Controller::class, 'update'])->name('my-apl01.update');
        Route::post('my-apl01/{apl01}/submit', [App\Http\Controllers\Assessee\MyApl01Controller::class, 'submit'])->name('my-apl01.submit');

        // My APL-02
        Route::get('my-apl02', [App\Http\Controllers\Assessee\MyApl02Controller::class, 'index'])->name('my-apl02.index');
        Route::get('my-apl02/{unit}', [App\Http\Controllers\Assessee\MyApl02Controller::class, 'show'])->name('my-apl02.show');
        Route::get('my-apl02/{unit}/upload', [App\Http\Controllers\Assessee\MyApl02Controller::class, 'uploadEvidence'])->name('my-apl02.upload');
        Route::post('my-apl02/{unit}/upload', [App\Http\Controllers\Assessee\MyApl02Controller::class, 'storeEvidence'])->name('my-apl02.store-evidence');
        Route::delete('my-apl02/{unit}/evidence/{evidence}', [App\Http\Controllers\Assessee\MyApl02Controller::class, 'deleteEvidence'])->name('my-apl02.delete-evidence');
        Route::post('my-apl02/{unit}/submit', [App\Http\Controllers\Assessee\MyApl02Controller::class, 'submit'])->name('my-apl02.submit');

        // My Assessments
        Route::get('my-assessments', [App\Http\Controllers\Assessee\MyAssessmentController::class, 'index'])->name('my-assessments.index');
        Route::get('my-assessments/{assessment}', [App\Http\Controllers\Assessee\MyAssessmentController::class, 'show'])->name('my-assessments.show');

        // My Payments
        Route::get('my-payments', [App\Http\Controllers\Assessee\MyPaymentController::class, 'index'])->name('my-payments.index');
        Route::get('my-payments/{payment}', [App\Http\Controllers\Assessee\MyPaymentController::class, 'show'])->name('my-payments.show');
        Route::get('my-payments/{payment}/upload-proof', [App\Http\Controllers\Assessee\MyPaymentController::class, 'uploadProof'])->name('my-payments.upload-proof');
        Route::post('my-payments/{payment}/upload-proof', [App\Http\Controllers\Assessee\MyPaymentController::class, 'storeProof'])->name('my-payments.store-proof');
    });
});
