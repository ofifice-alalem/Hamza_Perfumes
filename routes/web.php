<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PerfumePriceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        if (auth()->user()->isSaler()) {
            return redirect('/sales');
        }
        return redirect('/dashboard');
    });

    // Dashboard routes - super-admin only
    Route::middleware('role:super-admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('api/sales-analytics', [App\Http\Controllers\DashboardController::class, 'getSalesAnalytics']);
        Route::get('api/export-sales-analytics', [App\Http\Controllers\DashboardController::class, 'exportSalesAnalytics']);
    });

    // Users - super-admin only
    Route::middleware('role:super-admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users/check-username', [UserController::class, 'checkUsername']);
    });

    // Search routes - available for all authenticated users
    Route::get('/perfumes/search', [PerfumeController::class, 'search'])->name('perfumes.search');
    Route::get('/perfumes/search-uncategorized', [PerfumeController::class, 'searchUncategorized'])->name('perfumes.searchUncategorized');
    Route::get('/api/get-perfume-by-id/{id}', [PerfumeController::class, 'getPerfumeById']);

    // Admin and super-admin routes
    Route::middleware('role:super-admin,admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('perfumes', PerfumeController::class);
        Route::get('/perfumes/check-unique', [PerfumeController::class, 'checkUnique'])->name('perfumes.checkUnique');
        Route::resource('sizes', SizeController::class);
        Route::resource('prices', PerfumePriceController::class);
        Route::get('api/get-perfume-prices/{perfume}', [PerfumePriceController::class, 'getPerfumePrices']);
    });

    // Sales - all roles
    Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
    Route::put('sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::delete('sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::get('api/get-price', [SaleController::class, 'getPrice']);
    Route::get('api/get-available-sizes/{perfume}', [SaleController::class, 'getAvailableSizes']);
});
