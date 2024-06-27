<?php

use App\Http\Controllers\Api\v1\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/users', [TestController::class, 'index']);
