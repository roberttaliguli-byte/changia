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

// ========== PUBLIC ROUTES (No Auth Required) ==========
Route::get('/', fn() => view('home'))->name('home');

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
    
    // Events - Full resource
    Route::resource('events', EventController::class);
    
    // Contributors (Authenticated)
    Route::prefix('events/{event}')->group(function () {
        Route::get('/contributors', [ContributionController::class, 'index'])->name('contributors.index');
        Route::get('/contributors/create', [ContributionController::class, 'create'])->name('contributors.create');
        Route::post('/contributors', [ContributionController::class, 'store'])->name('contributors.store');
        Route::post('/contributors/{contributor}/add-payment', [ContributionController::class, 'addPayment'])->name('contributors.add.payment');
    });
    
    // Accountant (Mhasibu) routes for confirming contributions
    Route::middleware(['changia:accountant'])->prefix('mhasibu')->name('mhasibu.')->group(function () {
        Route::get('/confirm', [MhasibuController::class, 'confirm'])->name('confirm');
        Route::post('/approve/{contribution}', [MhasibuController::class, 'approve'])->name('approve');
        Route::post('/reject/{contribution}', [MhasibuController::class, 'reject'])->name('reject');
    });
    
    // Approve contributions
    Route::post('/contributions/{contribution}/approve', [ContributionController::class, 'approve'])->name('contributions.approve');
    Route::post('/contributions/{contribution}/reject', [ContributionController::class, 'reject'])->name('contributions.reject');
    
    // Mhasibu Management
    Route::prefix('mhasibu')->group(function () {
        Route::get('/', [MhasibuController::class, 'index'])->name('mhasibu.index');
        Route::get('/create', [MhasibuController::class, 'create'])->name('mhasibu.create');
        Route::post('/store', [MhasibuController::class, 'store'])->name('mhasibu.store');
        Route::delete('/{id}', [MhasibuController::class, 'destroy'])->name('mhasibu.destroy');
    });
    
    // Cards
    Route::prefix('cards')->group(function () {
        Route::get('/create', [CardController::class, 'create'])->name('cards.create');
        Route::post('/store', [CardController::class, 'store'])->name('cards.store');
        Route::get('/send', [CardController::class, 'send'])->name('cards.send');
        Route::post('/dispatch', [CardController::class, 'dispatch'])->name('cards.dispatch');
    });
    
    // Ujumbe
    Route::prefix('ujumbe')->group(function () {
        Route::get('/michango', [UjumbeController::class, 'michango'])->name('ujumbe.michango');
        Route::get('/mwaliko', [UjumbeController::class, 'mwaliko'])->name('ujumbe.mwaliko');
        Route::post('/tuma-michango', [UjumbeController::class, 'tumaMichango'])->name('ujumbe.tuma.michango');
        Route::post('/tuma-mwaliko', [UjumbeController::class, 'tumaMwaliko'])->name('ujumbe.tuma.mwaliko');
        Route::post('/reminder', [UjumbeController::class, 'sendReminder'])->name('ujumbe.reminder');
    });
});