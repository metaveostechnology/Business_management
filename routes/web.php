<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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
