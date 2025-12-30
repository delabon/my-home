<?php

declare(strict_types=1);

use App\Http\Controllers\Dashboard\PostController as DashboardPostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/blog/{post:slug}', PostController::class)->name('posts.view');

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
        Route::inertia('/', 'dashboard/Dashboard')
            ->name('dashboard');

        // Posts
        Route::prefix('posts')->name('posts')->group(function () {
            Route::get('/', [DashboardPostController::class, 'index'])
                ->name('.index');
            Route::post('/', [DashboardPostController::class, 'store'])
                ->middleware(['throttle:10,1']) // 10 attempts per minute
                ->name('.store');
            Route::get('create', [DashboardPostController::class, 'create'])
                ->name('.create');
            Route::get('{post}/edit', [DashboardPostController::class, 'edit'])
                ->name('.edit');
            Route::patch('{post}', [DashboardPostController::class, 'update'])
                ->middleware(['throttle:10,1']) // 10 attempts per minute
                ->name('.update');
            Route::delete('{post}', [DashboardPostController::class, 'destroy'])
                ->middleware(['throttle:10,1']) // 10 attempts per minute
                ->name('.destroy');
        });
    });
