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
});
