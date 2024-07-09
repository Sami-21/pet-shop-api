<?php

use App\Http\Controllers\Api\v1\BrandController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Services\SwaggerService;

Route::prefix('/v1')->group(function () {

    Route::controller(UserController::class)->prefix('/user')->group(function () {
        Route::get('/', 'me')->middleware(['auth.jwt']);
        Route::delete('/', 'destroy')->middleware(['auth.jwt']);
        Route::get('/orders', 'getOrders')->middleware(['auth.jwt']);
        Route::post('/create', 'store');
        Route::post('/forgot-password', 'forgetPassword');
        Route::post('/login', 'login');
        Route::get('/logout', 'logout')->middleware(['auth.jwt']);
        Route::post('/reset-password-token', 'resetPassword')->middleware(['auth.jwt']);
        Route::put('/edit', 'update')->middleware(['auth.jwt']);
    });

    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brand/{uuid}', [BrandController::class, 'index']);
    Route::controller(BrandController::class)->prefix('brand')->middleware(['auth.jwt', 'is.admin'])->group(function () {
        Route::post('/create', 'store');
        Route::put('/{uuid}', 'update');
        Route::delete('/{uuid}', 'destroy');
    });

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/category/{uuid}', [CategoryController::class, 'index']);
    Route::controller(CategoryController::class)->prefix('category')->middleware(['auth.jwt', 'is.admin'])->group(function () {
        Route::post('/create', 'store');
        Route::put('/{uuid}', 'update');
        Route::delete('/{uuid}', 'destroy');
    });
});
