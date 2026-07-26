<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JournalEntryController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        "name" => "API del sistema contable de Costa Tech",
        "version" => "v1.0.0-beta",
        "message" => "Esta es una API privada para el sistema contable. El acceso a los recursos requiere autenticación.",
        "environment" => app()->environment(),
        "meta" => [
            "timestamp" => now()->toIso8601String(),
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
        Route::get('/journal-entry', [JournalEntryController::class, 'index']);
        Route::post('/journal-entry', [JournalEntryController::class, 'store']);
    });
});
