<?php

use App\Http\Controllers\Dashboard\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::prefix('login')
    ->middleware(['guest'])
    ->group(function () {
        Route::inertia('/', 'auth/Login')
            ->name('login');

        Route::post('/', [LoginController::class, 'store'])
            ->middleware('throttle:5,1') // 5 attempts per minute
            ->name('login.store');
    });

Route::post('logout', [LoginController::class, 'destroy'])
    ->middleware(['auth', 'throttle:5,1']) // 5 attempts per minute
    ->name('logout');

Route::prefix('dashboard')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', function () {})
            ->name('dashboard');

        // Posts
        Route::prefix('posts')->name('posts')->group(function () {
            Route::get('/', [PostController::class, 'index'])
                ->name('.index');
            Route::post('/', [PostController::class, 'store'])
                ->middleware(['throttle: 10,1']) // 10 attempts per minute
                ->name('.store');
            Route::get('create', [PostController::class, 'create'])
                ->name('.create');
        });
    });
