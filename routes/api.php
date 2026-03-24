<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContributionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and will be assigned
| to the "api" middleware group. Enjoy building your API!
|
*/

// Public API Routes for SMS/WhatsApp Registration
Route::post('/register-contributor', [ContributionController::class, 'registerViaSMS'])->name('api.register.sms');
Route::get('/contribution-link/{token}', [ContributionController::class, 'showContributionForm'])->name('api.contribution.form');
Route::post('/submit-contribution', [ContributionController::class, 'submitViaLink'])->name('api.submit.contribution');