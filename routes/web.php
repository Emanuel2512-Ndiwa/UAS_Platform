<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

// ============================================================
// AUTH ROUTES (Guest Only)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// MIDTRANS WEBHOOK (tanpa auth/CSRF, dipanggil oleh server Midtrans)
// ============================================================
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ============================================================
// AUTHENTICATED ROUTES
// ============================================================
Route::middleware('auth')->group(function () {

    // ----------------------------------------------------------
    // ADMIN
    // ----------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard',                  [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',                      [AdminController::class, 'users'])->name('users');
        Route::get('/users/create',               [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users',                     [AdminController::class, 'storeUser'])->name('users.store');
        Route::patch('/users/{user}/toggle',      [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::delete('/users/{user}',            [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/transactions',               [AdminController::class, 'transactions'])->name('transactions');
        Route::patch('/transactions/{transaction}/cancel', [AdminController::class, 'cancelTransaction'])->name('transactions.cancel');
        Route::get('/transactions/{transaction}/wa',       [AdminController::class, 'generateWaLink'])->name('transactions.wa');
    });

    // ----------------------------------------------------------
    // KARYAWAN
    // ----------------------------------------------------------
    Route::middleware('role:karyawan,admin')->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/dashboard',                          [KaryawanController::class, 'dashboard'])->name('dashboard');
        Route::patch('/transactions/{transaction}/status',[KaryawanController::class, 'updateStatus'])->name('transactions.status');
        Route::get('/walkin/create',                      [KaryawanController::class, 'createWalkin'])->name('walkin.create');
        Route::post('/walkin',                            [KaryawanController::class, 'storeWalkin'])->name('walkin.store');
        Route::get('/inventaris',                         [KaryawanController::class, 'inventaris'])->name('inventaris');
        Route::get('/inventaris/create',                  [KaryawanController::class, 'createInventaris'])->name('inventaris.create');
        Route::post('/inventaris',                        [KaryawanController::class, 'storeInventaris'])->name('inventaris.store');
        Route::patch('/inventaris/{inventory}',           [KaryawanController::class, 'updateInventaris'])->name('inventaris.update');
        Route::delete('/inventaris/{inventory}',          [KaryawanController::class, 'destroyInventaris'])->name('inventaris.destroy');
    });

    // ----------------------------------------------------------
    // KURIR
    // ----------------------------------------------------------
    Route::middleware('role:kurir,admin')->prefix('kurir')->name('kurir.')->group(function () {
        Route::get('/dashboard',                           [KurirController::class, 'dashboard'])->name('dashboard');
        Route::patch('/orders/{transaction}/ambil',        [KurirController::class, 'ambilOrder'])->name('orders.ambil');
        Route::patch('/orders/{transaction}/selesai',      [KurirController::class, 'selesaikanOrder'])->name('orders.selesai');
        Route::put('/location',                            [KurirController::class, 'updateLocation'])->name('location.update');
    });

    // ----------------------------------------------------------
    // PELANGGAN / ORDER
    // ----------------------------------------------------------
    Route::middleware('role:pelanggan,admin')->prefix('order')->name('order.')->group(function () {
        Route::get('/',                    [OrderController::class, 'index'])->name('index');
        Route::get('/checkout/{service}',  [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/',                   [OrderController::class, 'store'])->name('store');
        Route::get('/history',             [OrderController::class, 'history'])->name('history');
        Route::get('/{transaction}',       [OrderController::class, 'show'])->name('show');
    });

    // ----------------------------------------------------------
    // PAYMENT
    // ----------------------------------------------------------
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{transaction}',            [PaymentController::class, 'checkoutPage'])->name('checkout');
        Route::post('/{transaction}/snap-token',[PaymentController::class, 'createSnapToken'])->name('snap_token');
    });

    // Redirect /dashboard ke dashboard role masing-masing
    Route::get('/dashboard/admin',     [AdminController::class, 'dashboard'])->name('dashboard.admin');
    Route::get('/dashboard/karyawan',  [KaryawanController::class, 'dashboard'])->name('dashboard.karyawan');
    Route::get('/dashboard/kurir',     [KurirController::class, 'dashboard'])->name('dashboard.kurir');
    Route::get('/dashboard/pelanggan', [OrderController::class, 'history'])->name('dashboard.pelanggan');
});

// ============================================================
// HOME
// ============================================================
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');
