<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me',     [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

// Auth Routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
