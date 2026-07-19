<?php

use App\Http\Controllers\RegisterCompleteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        "name" => "API del sistema contable de Costa Tech",
        "version" => "v1.0.0-beta",
        "message" => "Esta es una API privada para el sistema contable. El acceso a los recursos requiere autenticación.",
        "environment" => app()->environment(),
        "meta" => [
            "timestamp" => now()->toIso8601String(),
            "status" => "online",
        ]
    ]);
});

Route::get('/auth/register/complete', [RegisterCompleteController::class, 'showForm'])
    ->name('register.complete')
    ->middleware('signed');

Route::post('/auth/register/complete', [RegisterCompleteController::class, 'store'])
    ->name('register.complete.store')
    ->middleware('signed');
