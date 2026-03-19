<?php

use App\Http\Controllers\API\AdminAuthController;
use App\Http\Controllers\API\AdminCompanyController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\BranchUserController;
use App\Http\Controllers\API\CompanyAuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\DepartmentFeatureController;
use App\Http\Controllers\API\FeatureController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\SystemSettingController;
use App\Http\Controllers\Api\BranchAdminAuthController;
use App\Http\Controllers\Api\BranchEmployeeController;
use App\Http\Controllers\Api\DeptAdminAuthController;
use App\Http\Controllers\Api\DeptEmployeeController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Admin Management & Company API Routes |-------------------------------------------------------------------------- */

// ── Public Routes (Admin) ────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('admin.login');
});

// ── Public Routes (Company Self-Registration) ───────────────────────────────
Route::post('/register-company', [CompanyAuthController::class, 'register'])->name('company.self.register');

// ── Public Routes (Company Login via companies table) ────────────────────────
Route::post('/company/login', [CompanyAuthController::class, 'login'])->name('company.auth.login');

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

    // Admin: Company CRUD (with password support, soft-delete)
    Route::prefix('admin/companies')->group(function () {
        Route::get('/', [AdminCompanyController::class, 'index'])->name('admin.companies.index');
        Route::post('/', [AdminCompanyController::class, 'store'])->name('admin.companies.store');
        Route::get('/{slug}', [AdminCompanyController::class, 'show'])->name('admin.companies.show');
        Route::put('/{slug}', [AdminCompanyController::class, 'update'])->name('admin.companies.update');
        Route::delete('/{slug}', [AdminCompanyController::class, 'destroy'])->name('admin.companies.destroy');
    });
});

// ── Protected Routes (Sanctum - Company Users via companies table) ───────────
Route::middleware('auth:sanctum,company')->group(function () {

    // Company profile & password (using companies table)
    Route::prefix('company')->group(function () {
        Route::post('/logout', [CompanyAuthController::class, 'logout'])->name('company.auth.logout');
        Route::get('/profile', [CompanyAuthController::class, 'profile'])->name('company.auth.profile');
        Route::put('/profile', [CompanyAuthController::class, 'updateProfile'])->name('company.auth.profile.update');
        Route::post('/change-password', [CompanyAuthController::class, 'changePassword'])->name('company.auth.change-password');
    });

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

            // Department read-only routes (global lookup)
            Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
            Route::get('/departments/{slug}', [DepartmentController::class, 'show'])->name('departments.show');

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

            // Role management routes (global lookup, accessible by all company users)
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
            Route::get('/roles/{slug}', [RoleController::class, 'show'])->name('roles.show');
            Route::put('/roles/{slug}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('/roles/{slug}', [RoleController::class, 'destroy'])->name('roles.destroy');

            // Branch user management routes (company-scoped)
            Route::get('/branch-users', [BranchUserController::class, 'index'])->name('branch-users.index');
            Route::post('/branch-users', [BranchUserController::class, 'store'])->name('branch-users.store');
            Route::get('/branch-users/{slug}', [BranchUserController::class, 'show'])->name('branch-users.show');
            Route::put('/branch-users/{slug}', [BranchUserController::class, 'update'])->name('branch-users.update');
            Route::delete('/branch-users/{slug}', [BranchUserController::class, 'destroy'])->name('branch-users.destroy');
            Route::post('/branch-users/{slug}/change-password', [BranchUserController::class, 'changePassword'])->name('branch-users.change-password');
        }
    );
});

Route::post('/branch-admin/login', [BranchAdminAuthController::class, 'login']);

Route::middleware(['auth:sanctum,branch_admin', 'branch_admin'])->group(function () {

    Route::post('/branch-admin/logout', [BranchAdminAuthController::class, 'logout']);

    // Departments
    Route::get('/departments', [DepartmentController::class, 'index']);

    // Branch Employees
    Route::post('/branch/employees', [BranchEmployeeController::class, 'store']);
    Route::get('/branch/employees', [BranchEmployeeController::class, 'index']);
    Route::get('/branch/employees/{slug}', [BranchEmployeeController::class, 'show']);
    Route::put('/branch/employees/{slug}', [BranchEmployeeController::class, 'update']);
    Route::post('/branch/employees/{slug}', [BranchEmployeeController::class, 'update']); // For multipart/form-data support
    Route::delete('/branch/employees/{slug}', [BranchEmployeeController::class, 'destroy']);
});

Route::post('/dept-admin/login', [DeptAdminAuthController::class, 'login']);

Route::middleware(['auth:sanctum,dept_admin', 'dept_admin'])->group(function () {

    Route::post('/dept-admin/logout', [DeptAdminAuthController::class, 'logout']);

    // Dept Employees
    Route::post('/dept/employees', [DeptEmployeeController::class, 'store']);
    Route::get('/dept/employees', [DeptEmployeeController::class, 'index']);
    Route::get('/dept/employees/{slug}', [DeptEmployeeController::class, 'show']);
    Route::put('/dept/employees/{slug}', [DeptEmployeeController::class, 'update']);
    Route::delete('/dept/employees/{slug}', [DeptEmployeeController::class, 'destroy']);
});

