<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        "message" => "Sistema contable - API",
        "version" => "Beta"
    ]);
});
