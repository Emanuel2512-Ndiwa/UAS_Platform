<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KurirDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');

    });
// Dashboard umum
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Dashboard Kurir
Route::get('/kurir/dashboard', [KurirDashboardController::class, 'index'])
    ->middleware('auth');

//Fitur Pesanan(CRUD)
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
});
