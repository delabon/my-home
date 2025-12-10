<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::post('login', [LoginController::class, 'store'])
    ->middleware(['guest', 'throttle:5,1']) // 5 attempts per minute
    ->name('login.store');

Route::inertia('login', 'auth/Login')
    ->middleware(['guest'])
    ->name('login');

Route::get('dashboard', function () {})
    ->name('dashboard');
