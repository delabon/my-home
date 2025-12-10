<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::prefix('login')->middleware(['guest'])->group(function () {
    Route::inertia('/', 'auth/Login')
        ->name('login');

    Route::post('/', [LoginController::class, 'store'])
        ->middleware('throttle:5,1') // 5 attempts per minute
        ->name('login.store');
});

Route::post('logout', [LoginController::class, 'destroy'])
    ->middleware('throttle:5,1') // 5 attempts per minute
    ->name('logout');

Route::get('dashboard', function () {})
    ->name('dashboard');
