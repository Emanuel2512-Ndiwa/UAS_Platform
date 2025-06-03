<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KurirDashboardController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');

Route::get('/kurir/dashboard', [KurirDashboardController::class, 'index'])
    ->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
});
