<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Language Route
Route::post('/lang', [LanguageController::class, 'index'])->middleware('LanguageSwitcher')->name('lang');
Route::get('/lang/{lang}', [LanguageController::class, 'change'])->middleware('LanguageSwitcher')->name('langChange');

// Social Auth
Route::get('/oauth/{driver}', [SocialAuthController::class, 'redirectToProvider'])->name('social.oauth');
Route::get('/oauth/{driver}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');

// ========== ROOT URL DIRECTLY ADMIN LOGIN PAR REDIRECT ==========
Route::get('/', function () {
    return redirect('/admin');
});

// ========== ADMIN AUTH ROUTES ==========
Route::Group(['prefix' => 'admin'], function () {
    Auth::routes();
});

// ========== PROTECTED ADMIN ROUTES ==========
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/admin', [App\Http\Controllers\HomeController::class, 'dashboard'])->name
    ('admin.dashboard');
    // ... baki admin panel routes
});

// ========== APIs FOR REACT FRONTEND ==========
Route::prefix('api')->group(function () {
    // APIs for React frontend
});

// ========== ALL OTHER URLs REDIRECT TO ADMIN LOGIN ==========
Route::any('{any}', function () {
    return redirect('/admin');
})->where('any', '.*');

