<?php

use App\Http\Controllers\API\AdminAuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\CompanyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Management & Company API Routes
|--------------------------------------------------------------------------
*/

// ── Public Routes (Admin) ────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('admin.login');
});

// ── Public Routes (Company Users) ────────────────────────────────────────────
Route::post('/register', [\App\Http\Controllers\API\AuthController::class, 'register'])->name('company.register');
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login'])->name('company.login');

// ── Protected Routes (Sanctum - Admins) ──────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth profile routes (Admin)
    Route::prefix('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/profile', [AdminAuthController::class, 'profile'])->name('admin.profile');
        Route::put('/profile', [AdminAuthController::class, 'updateProfile'])->name('admin.profile.update');
    });

    // Admin management routes (slug-based)
    Route::prefix('admins')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admins.index');
        Route::post('/', [AdminController::class, 'store'])->name('admins.store');
        Route::get('/{slug}', [AdminController::class, 'show'])->name('admins.show');
        Route::put('/{slug}', [AdminController::class, 'update'])->name('admins.update');
        Route::post('/{slug}', [AdminController::class, 'update'])->name('admins.update.post');
        Route::delete('/{slug}', [AdminController::class, 'destroy'])->name('admins.destroy');
        Route::post('/{slug}/restore', [AdminController::class, 'restore'])->name('admins.restore');
    });

    // Company management routes (slug-based)
    Route::prefix('companies')->group(
        function () {
            Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
            Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
            Route::get('/{slug}', [CompanyController::class, 'show'])->name('companies.show');
            Route::put('/{slug}', [CompanyController::class, 'update'])->name('companies.update');
            // POST route for update — required for multipart/form-data logo upload
            // (HTTP PUT cannot carry file uploads in many clients)
            // Use: POST /api/companies/{slug}?_method=PUT  or just POST with form-data
            Route::post('/{slug}', [CompanyController::class, 'update'])->name('companies.update.post');
            Route::delete('/{slug}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        }
    );
});

// ── Protected Routes (Sanctum - Company Users) ───────────────────────────────
Route::middleware('auth:sanctum,company')->group(function () {

    // Company User Auth routes
    Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->name('company.logout');

    // Company management routes (slug-based)
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/{slug}', [CompanyController::class, 'show'])->name('companies.show');
        Route::put('/{slug}', [CompanyController::class, 'update'])->name('companies.update');
        Route::post('/{slug}', [CompanyController::class, 'update'])->name('companies.update.post');
        Route::delete('/{slug}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    });
});
