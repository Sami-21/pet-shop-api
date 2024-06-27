<?php

use App\Http\Controllers\Api\v1\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', [TestController::class, 'index']);
