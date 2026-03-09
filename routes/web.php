<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin management routes (Web)
Route::resource('admins', AdminController::class);

// Company management routes (Web)
Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
