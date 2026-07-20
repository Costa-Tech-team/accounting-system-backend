<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        "name" => "API del sistema contable de Costa Tech",
        "version" => "v1.0.0-beta",
        "message" => "Esta es una API privada para el sistema contable. El acceso a los recursos requiere autenticación.",
        "environment" => app()->environment(),
        "meta" => [
            "timestamp" => "2026-07-19T22:15:30+00:00",
            "version" => "v1",
            "status" => "online",
        ]
    ]);
});

Route::prefix('/v1')->group(function () {
    // Auth Routes
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Accounting System Core Routes
        Route::get('/account', [AccountController::class, 'index']);
        Route::get('/account/{id}', [AccountController::class, 'show']);
        Route::post('/account', [AccountController::class, 'store']);
    });
});
