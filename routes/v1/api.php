<?php

use App\Http\Controllers\Api\v1\BrandController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/brand/{uuid}', [BrandController::class, 'show']);
    Route::controller(BrandController::class)->prefix('brand')->middleware(['auth.jwt', 'is.admin'])->group(function () {
        Route::post('/create', 'store');
        Route::put('/{uuid}', 'update');
        Route::delete('/{uuid}', 'destroy');
    });

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/category/{uuid}', [CategoryController::class, 'show']);
    Route::controller(CategoryController::class)->prefix('category')->middleware(['auth.jwt', 'is.admin'])->group(function () {
        Route::post('/create', 'store');
        Route::put('/{uuid}', 'update');
        Route::delete('/{uuid}', 'destroy');
    });

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{uuid}', [ProductController::class, 'show']);
    Route::controller(ProductController::class)->prefix('product')->middleware(['auth.jwt', 'is.admin'])->group(function () {
        Route::post('/create', 'store');
        Route::put('/{uuid}', 'update');
        Route::delete('/{uuid}', 'destroy');
    });
});
