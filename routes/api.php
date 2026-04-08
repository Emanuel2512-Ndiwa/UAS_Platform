<?php

use App\Http\Controllers\KurirController;
use Illuminate\Support\Facades\Route;

// API untuk update GPS kurir (dipanggil dari browser kurir)
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/kurir/location', [KurirController::class, 'updateLocation']);
});
