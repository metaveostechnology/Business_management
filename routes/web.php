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
    Route::get('/departments', [CompanyFrontendController::class, 'departments'])->name('departments');
    Route::get('/roles', [CompanyFrontendController::class, 'roles'])->name('roles');
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
