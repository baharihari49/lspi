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
});
