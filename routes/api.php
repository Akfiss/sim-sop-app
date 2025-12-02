<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Public Routes (Bisa diakses tanpa login)
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Harus login & punya token)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Test user data
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
});
