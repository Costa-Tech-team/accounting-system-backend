<?php

use App\Http\Controllers\RegisterCompleteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        "message" => "Sistema contable - API",
        "version" => "Beta"
    ]);
});

Route::get('/auth/register/complete', [RegisterCompleteController::class, 'showForm'])
    ->name('register.complete')
    ->middleware('signed');

Route::post('/auth/register/complete', [RegisterCompleteController::class, 'store'])
    ->name('register.complete.store')
    ->middleware('signed');
