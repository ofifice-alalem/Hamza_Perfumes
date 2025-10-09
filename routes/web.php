<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PerfumePriceController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return redirect()->route('perfumes.index');
});

Route::resource('perfumes', PerfumeController::class);
Route::resource('sizes', SizeController::class);
Route::resource('categories', CategoryController::class);
Route::resource('prices', PerfumePriceController::class);
Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
Route::get('api/get-price', [SaleController::class, 'getPrice']);
Route::get('api/get-perfume-prices/{perfume}', [PerfumePriceController::class, 'getPerfumePrices']);
