<?php

use App\Http\Controllers\API\AdminAuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Management API Routes
|--------------------------------------------------------------------------
|
| Public Routes  : Login (with rate limiting)
| Protected Routes: All other admin-related endpoints (auth:sanctum)
|
*/

// ── Public Routes ────────────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    // Rate-limited login: max 5 attempts per minute
    Route::post('/login', [AdminAuthController::class, 'login'])
         ->middleware('throttle:5,1')
         ->name('admin.login');
});

// ── Protected Routes (Sanctum) ───────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth profile routes
    Route::prefix('admin')->group(function () {
        Route::post('/logout',  [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/profile',  [AdminAuthController::class, 'profile'])->name('admin.profile');
        Route::put('/profile',  [AdminAuthController::class, 'updateProfile'])->name('admin.profile.update');
    });

    // Admin management routes (slug-based)
    Route::prefix('admins')->group(function () {
        Route::get('/',                [AdminController::class, 'index'])->name('admins.index');
        Route::post('/',               [AdminController::class, 'store'])->name('admins.store');
        Route::get('/{slug}',          [AdminController::class, 'show'])->name('admins.show');
        Route::put('/{slug}',          [AdminController::class, 'update'])->name('admins.update');
        Route::delete('/{slug}',       [AdminController::class, 'destroy'])->name('admins.destroy');
        Route::post('/{slug}/restore', [AdminController::class, 'restore'])->name('admins.restore');
    });

    // Company management routes (slug-based)
    Route::prefix('companies')->group(function () {
        Route::get('/',           [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/',          [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/{slug}',     [CompanyController::class, 'show'])->name('companies.show');
        Route::put('/{slug}',     [CompanyController::class, 'update'])->name('companies.update');
        // POST route for update — required for multipart/form-data logo upload
        // (HTTP PUT cannot carry file uploads in many clients)
        // Use: POST /api/companies/{slug}?_method=PUT  or just POST with form-data
        Route::post('/{slug}',    [CompanyController::class, 'update'])->name('companies.update.post');
        Route::delete('/{slug}',  [CompanyController::class, 'destroy'])->name('companies.destroy');
    });
});
