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
    });
});
