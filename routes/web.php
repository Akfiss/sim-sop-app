<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController; // <--- Pastikan di-import

// Arahkan ke Controller agar logic $stats dijalankan
Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');
