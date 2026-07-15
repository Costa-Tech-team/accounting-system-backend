<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        "message" => "Sistema contable - API",
        "version" => "Beta"
    ]);
});

// Auth Routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
