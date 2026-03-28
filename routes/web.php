<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\CompanyFrontendController;

Route::get('/', function () {
    return redirect()->route('company.frontend.login');
});

// Company Frontend Routes (Interacts mainly visually and uses JS to call API)
Route::name('company.frontend.')->prefix('company')->group(function () {
    // Public routes
    Route::get('/login', [CompanyFrontendController::class, 'login'])->name('login');
    Route::get('/register', [CompanyFrontendController::class, 'register'])->name('register');
    
    // Protected routes (handled on client side via JS checking localStorage auth tokens)
    Route::get('/dashboard', [CompanyFrontendController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CompanyFrontendController::class, 'profile'])->name('profile');
    Route::get('/branches', [CompanyFrontendController::class, 'branches'])->name('branches');
    Route::get('/branch-users', [CompanyFrontendController::class, 'branchUsers'])->name('branch-users');
});

// App Admin Routes
Route::prefix('appadmin')->group(function () {
    // Guest Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Authenticated Routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Dashboard (redirects to admins list for now as per "App Admin only can see other app admins")
          Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
       
        // Admin Management
        Route::resource('admins', AdminController::class);

        // Company Management
        Route::resource('companies', CompanyController::class);
    });
});
// Branch Admin Routes
Route::name('branch.')->prefix('branch')->group(function () {
    // Public routes
    Route::get('/login', [App\Http\Controllers\Web\BranchAdminController::class, 'login'])->name('login');
    
    // Protected routes (handled on client side via JS checking localStorage auth tokens)
    Route::get('/dashboard', [App\Http\Controllers\Web\BranchAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/employees', [App\Http\Controllers\Web\BranchAdminController::class, 'employees'])->name('employees');
});

// Department Admin Routes
Route::name('department.')->prefix('department')->group(function () {
    // Public routes
    Route::get('/login', [App\Http\Controllers\Web\DeptAdminController::class, 'login'])->name('login');
    
    // Protected routes (handled on client side via JS checking localStorage auth tokens)
    Route::get('/dashboard', [App\Http\Controllers\Web\DeptAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/employees', [App\Http\Controllers\Web\DeptAdminController::class, 'employees'])->name('employees');
});

// Department Employee Routes
Route::name('employee.')->prefix('employee')->group(function () {
    // Public routes
    Route::get('/login', [App\Http\Controllers\Web\DeptEmployeeDashboardController::class, 'login'])->name('login');
    // Protected routes
    Route::get('/dashboard/{slug}', [App\Http\Controllers\Web\DeptEmployeeDashboardController::class, 'dashboard'])->name('dashboard');
});

// Employee Self-Service Dashboard Routes
Route::name('employee_self.')->prefix('employee-self')->group(function () {
    // Public routes
    Route::get('/login', [App\Http\Controllers\Web\EmployeeSelfDashboardController::class, 'login'])->name('login');
    // Protected routes (handled on client side checking localStorage token)
    Route::get('/dashboard', [App\Http\Controllers\Web\EmployeeSelfDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Web\EmployeeSelfDashboardController::class, 'profile'])->name('profile');
    Route::get('/leaves', [App\Http\Controllers\Web\EmployeeSelfDashboardController::class, 'leaves'])->name('leaves');
});
