<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PerfumePriceController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/perfumes/check-unique', [PerfumeController::class, 'checkUnique'])->name('perfumes.checkUnique');
Route::get('/perfumes/search', [PerfumeController::class, 'search'])->name('perfumes.search');
Route::get('/perfumes/search-uncategorized', [PerfumeController::class, 'searchUncategorized'])->name('perfumes.searchUncategorized');
Route::get('/api/get-perfume-by-id/{id}', [PerfumeController::class, 'getPerfumeById']);

Route::resource('perfumes', PerfumeController::class);
Route::resource('sizes', SizeController::class);
Route::resource('categories', CategoryController::class);
Route::resource('prices', PerfumePriceController::class);
Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
Route::get('api/get-price', [SaleController::class, 'getPrice']);
Route::get('api/get-perfume-prices/{perfume}', [PerfumePriceController::class, 'getPerfumePrices']);
Route::get('api/get-available-sizes/{perfume}', [SaleController::class, 'getAvailableSizes']);
Route::get('api/sales-analytics', [App\Http\Controllers\DashboardController::class, 'getSalesAnalytics']);
Route::get('api/export-sales-analytics', [App\Http\Controllers\DashboardController::class, 'exportSalesAnalytics']);
