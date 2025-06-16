<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TransactionController;


Route::get('/cek-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Koneksi database berhasil: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return 'Gagal konek DB: ' . $e->getMessage();
    }
});


Route::get('/', [ServiceController::class, 'indexHome'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

//Halaman login dan register (khusus untuk guest / belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'index'])->name('login');
    Route::post('/login', [UserController::class, 'login_process'])->name('login.process');

    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::post('/register', [UserController::class, 'register_process'])->name('register.process');
});


// Authenticated user
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
    Route::get('/dashboard/karyawan', [DashboardController::class, 'karyawanDashboard'])->name('dashboard.karyawan');
    Route::get('/dashboard/kurir', [DashboardController::class, 'kurirDashboard'])->name('dashboard.kurir');
    Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])->name('dashboard.pelanggan');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transaction.store');
    Route::post('/transactions/create', [TransactionController::class, 'create_transaction'])->name('transaction.create');
    Route::put('/transactions/{id}/status', [TransactionController::class, 'update_status'])->name('transaction.update_status');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transaction.show');
    Route::delete('/transactions/{id}', [TransactionController::class, 'delete_transaction'])->name('transaction.delete');


    // Halaman khusus kurir melihat order
    Route::get('/kurir/orders', [TransactionController::class, 'kurirOrders'])->name('transaction.kurir_orders');
});
