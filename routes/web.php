<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\AuthController;
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
        Route::get('/dashboard', function() {
            return redirect()->route('admins.index');
        })->name('dashboard');

        // Admin Management
        Route::resource('admins', AdminController::class);

        // Company Management
        Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
    });
});
