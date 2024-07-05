<?php

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
});
