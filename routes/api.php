<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Middleware\CheckApiKey;

Route::middleware(CheckApiKey::class)->group(function () {

    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/stocks', [StockController::class, 'index']);
    Route::get('/incomes', [IncomeController::class, 'index']);
});