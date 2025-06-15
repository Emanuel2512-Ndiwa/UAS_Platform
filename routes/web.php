<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/regist', [AuthController::class, 'showRegist'])->name('regist');
Route::post('/regist', [AuthController::class, 'regist'])->name('regist.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// contoh route dashboard (pastikan buat sesuai role dan middleware)
Route::get('/dashboard', function () {
    return "Dashboard - akses terbatas setelah login";
})->middleware('auth')->name('dashboard');
use App\Http\Controllers\KurirDashboardController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');

Route::get('/kurir/dashboard', [KurirDashboardController::class, 'index'])
    ->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
});
