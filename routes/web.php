<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;

Route::get('/', [ServiceController::class, 'indexHome'])->name('home');

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login-process', [AuthController::class, 'loginProcess'])->name('login-process');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register.process', [AuthController::class, 'register'])->name('register.process');

});
// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])->name('pelanggan.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

