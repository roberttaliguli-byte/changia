<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MhasibuController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\UjumbeController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\NotificationController;

// ========== PUBLIC ROUTES (No Auth Required) ==========
Route::get('/', fn() => view('home'))->name('home');

// ========== PUBLIC REGISTRATION FOR CONTRIBUTORS (Link-based) ==========
Route::get('/register/{token}', [EventController::class, 'showPublicRegistrationForm'])->name('public.contributor.register');
Route::post('/register/{token}', [EventController::class, 'storePublicRegistration'])->name('public.contributor.store');

// Public registration for contributors via SMS link (NO AUTH REQUIRED)
Route::get('/register-contributor/{event}/{phone?}', [ContributorController::class, 'publicRegisterForm'])
    ->name('contributor.register.form');
    
Route::post('/register-contributor/{event}', [ContributorController::class, 'publicStore'])
    ->name('contributor.public.store');

// Public payment form for contributors (NO AUTH REQUIRED)
Route::get('/payment/{contributor}', [ContributorController::class, 'publicPaymentForm'])
    ->name('contributor.payment.form');
    
Route::post('/payment/{contributor}', [ContributorController::class, 'publicPaymentStore'])
    ->name('contributor.payment.store');

// Public card view (NO AUTH REQUIRED)
Route::get('/cards/view/{shareLink}', [CardController::class, 'view'])->name('cards.view');

// Guest Routes (Auth)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ========== AUTHENTICATED ROUTES ==========
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::post('/reset', [SettingController::class, 'resetDefaults'])->name('reset');
        Route::post('/toggle-theme', [SettingController::class, 'toggleTheme'])->name('toggle-theme');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
        Route::get('/events', [ReportController::class, 'events'])->name('events');
        Route::get('/contributions', [ReportController::class, 'contributions'])->name('contributions');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });
    
    // ========== EVENTS (Full Resource) ==========
    Route::resource('events', EventController::class);
    
    // ========== EVENT REGISTRATION LINK GENERATION ==========
    Route::get('/events/{event}/get-registration-link', [EventController::class, 'getRegistrationLink'])->name('events.get-registration-link');
    Route::get('/events/{event}/generate-link', [EventController::class, 'generateRegistrationLink'])->name('events.generate-link');
    
    // ========== CONTRIBUTORS & CONTRIBUTIONS ==========
    Route::prefix('events/{event}')->group(function () {
        // Contributor listing and management
        Route::get('/contributors', [ContributionController::class, 'index'])->name('contributors.index');
        Route::get('/contributors/create', [ContributionController::class, 'create'])->name('contributors.create');
        Route::post('/contributors', [ContributionController::class, 'store'])->name('contributors.store');
        
        // Edit and Update contributor
        Route::get('/contributors/{contributor}/edit', [ContributionController::class, 'edit'])->name('contributors.edit');
        Route::put('/contributors/{contributor}', [ContributionController::class, 'update'])->name('contributors.update');
        
        // Delete contributor
        Route::delete('/contributors/{contributor}', [ContributionController::class, 'destroy'])->name('contributors.destroy');
        
        // Add payment to contributor
        Route::post('/contributors/{contributor}/add-payment', [ContributionController::class, 'addPayment'])->name('contributors.add.payment');
        
        // Export routes
        Route::get('/export/pdf', [ContributionController::class, 'exportPDF'])->name('contributors.export.pdf');
        Route::get('/export/excel', [ContributionController::class, 'exportExcel'])->name('contributors.export.excel');
    });
    
    // ========== CONTRIBUTION APPROVAL (Both Accountant and Event Owner) ==========
    Route::post('/contributions/{contribution}/approve', [ContributionController::class, 'approve'])->name('contributions.approve');
    Route::post('/contributions/{contribution}/reject', [ContributionController::class, 'reject'])->name('contributions.reject');
    
    // ========== MHASIBU (Accountant) Management ==========
    Route::prefix('mhasibu')->group(function () {
        Route::get('/', [MhasibuController::class, 'index'])->name('mhasibu.index');
        Route::get('/create', [MhasibuController::class, 'create'])->name('mhasibu.create');
        Route::post('/store', [MhasibuController::class, 'store'])->name('mhasibu.store');
        Route::delete('/{id}', [MhasibuController::class, 'destroy'])->name('mhasibu.destroy');
    });
    
    // ========== MHASIBU Confirmation Routes (Accountant only) ==========
    Route::middleware(['changia:accountant'])->prefix('mhasibu')->name('mhasibu.')->group(function () {
        Route::get('/confirm', [MhasibuController::class, 'confirm'])->name('confirm');
        Route::post('/approve/{contribution}', [MhasibuController::class, 'approve'])->name('approve');
        Route::post('/reject/{contribution}', [MhasibuController::class, 'reject'])->name('reject');
    });
    
    // ========== UJUMBE Routes (Messages) ==========
    Route::prefix('ujumbe')->group(function () {
        Route::get('/michango', [UjumbeController::class, 'michango'])->name('ujumbe.michango');
        Route::get('/mwaliko', [UjumbeController::class, 'mwaliko'])->name('ujumbe.mwaliko');
        Route::post('/tuma-michango', [UjumbeController::class, 'tumaMichango'])->name('ujumbe.tuma.michango');
        Route::post('/tuma-mwaliko', [UjumbeController::class, 'tumaMwaliko'])->name('ujumbe.tuma.mwaliko');
        Route::post('/send-reminders', [UjumbeController::class, 'sendReminders'])->name('ujumbe.send.reminders');
        Route::post('/send-single-reminder', [UjumbeController::class, 'sendSingleReminder'])->name('ujumbe.send.single.reminder');
    });
    
    // ========== CARDS Routes (Invitation Cards) - AUTHENTICATED ==========
    Route::prefix('cards')->name('cards.')->group(function () {
        Route::get('/create', [CardController::class, 'create'])->name('create');
        Route::post('/store', [CardController::class, 'store'])->name('store');
        Route::get('/send', [CardController::class, 'send'])->name('send');
        Route::post('/share', [CardController::class, 'share'])->name('share');
        Route::get('/data/{id}', [CardController::class, 'getCardData'])->name('data');
    });
    
    // ========== SMS EXPORT ROUTES ==========
    Route::get('/ujumbe/download-export', [UjumbeController::class, 'downloadSMSExport'])->name('ujumbe.download.export');
    
    // ========== API Routes ==========
    Route::prefix('api')->group(function () {
        Route::get('/event/{event}/contributors', [ContributionController::class, 'getContributorsApi'])->name('api.event.contributors');
        Route::get('/event/{eventId}/contributors', [UjumbeController::class, 'getEventContributors']);
        Route::get('/event/{eventId}/details', function($eventId) {
            $event = App\Models\Event::findOrFail($eventId);
            return response()->json([
                'event_name' => $event->event_name,
                'event_date' => $event->event_date->format('d/m/Y')
            ]);
        });
    });
    
    // ========== NOTIFICATIONS ==========
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});