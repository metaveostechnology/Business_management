<?php

use App\Http\Controllers\API\AdminAuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\DepartmentFeatureController;
use App\Http\Controllers\API\FeatureController;
use App\Http\Controllers\API\SystemSettingController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Admin Management & Company API Routes |-------------------------------------------------------------------------- */

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
    Route::prefix('admin')->group(
        function () {
            Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
            Route::get('/profile', [AdminAuthController::class, 'profile'])->name('admin.profile');
            Route::put('/profile', [AdminAuthController::class, 'updateProfile'])->name('admin.profile.update');
        }
    );

    // Admin management routes (slug-based)
    Route::prefix('admins')->group(
        function () {
            Route::get('/', [AdminController::class, 'index'])->name('admins.index');
            Route::post('/', [AdminController::class, 'store'])->name('admins.store');
            Route::get('/{slug}', [AdminController::class, 'show'])->name('admins.show');
            Route::put('/{slug}', [AdminController::class, 'update'])->name('admins.update');
            Route::post('/{slug}', [AdminController::class, 'update'])->name('admins.update.post');
            Route::delete('/{slug}', [AdminController::class, 'destroy'])->name('admins.destroy');
            Route::post('/{slug}/restore', [AdminController::class, 'restore'])->name('admins.restore');
        }
    );

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
    Route::prefix('companies')->group(
        function () {
            Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
            Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
            Route::get('/{slug}', [CompanyController::class, 'show'])->name('companies.show');
            Route::put('/{slug}', [CompanyController::class, 'update'])->name('companies.update');
            Route::post('/{slug}', [CompanyController::class, 'update'])->name('companies.update.post');
            Route::delete('/{slug}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        }
    );

    // Branch management routes (slug-based, scoped to authenticated company)
    Route::prefix('company')->group(
        function () {
            Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
            Route::post('/branches', [BranchController::class, 'store'])->name('branches.store');
            Route::get('/branches/{slug}', [BranchController::class, 'show'])->name('branches.show');
            Route::put('/branches/{slug}', [BranchController::class, 'update'])->name('branches.update');
            Route::delete('/branches/{slug}', [BranchController::class, 'destroy'])->name('branches.destroy');

            // Feature management routes (slug-based, ordered by sort_order)
            Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
            Route::post('/features', [FeatureController::class, 'store'])->name('features.store');
            Route::get('/features/{slug}', [FeatureController::class, 'show'])->name('features.show');
            Route::put('/features/{slug}', [FeatureController::class, 'update'])->name('features.update');
            Route::delete('/features/{slug}', [FeatureController::class, 'destroy'])->name('features.destroy');

            // Department management routes (slug-based, scoped to authenticated company)
            Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
            Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
            Route::get('/departments/{slug}', [DepartmentController::class, 'show'])->name('departments.show');
            Route::put('/departments/{slug}', [DepartmentController::class, 'update'])->name('departments.update');
            Route::delete('/departments/{slug}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

            // Department-Feature mapping routes (slug-based, company-scoped via department)
            Route::get('/department-features', [DepartmentFeatureController::class, 'index'])->name('department-features.index');
            Route::post('/department-features', [DepartmentFeatureController::class, 'store'])->name('department-features.store');
            Route::get('/department-features/{slug}', [DepartmentFeatureController::class, 'show'])->name('department-features.show');
            Route::put('/department-features/{slug}', [DepartmentFeatureController::class, 'update'])->name('department-features.update');
            Route::delete('/department-features/{slug}', [DepartmentFeatureController::class, 'destroy'])->name('department-features.destroy');

            // System settings routes (company-scoped, slug = setting_group + setting_key)
            Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
            Route::post('/settings', [SystemSettingController::class, 'store'])->name('settings.store');
            Route::get('/settings/{slug}', [SystemSettingController::class, 'show'])->name('settings.show');
            Route::put('/settings/{slug}', [SystemSettingController::class, 'update'])->name('settings.update');
            Route::delete('/settings/{slug}', [SystemSettingController::class, 'destroy'])->name('settings.destroy');
        }
    );
});
