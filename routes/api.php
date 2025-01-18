<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RegisterController;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [LoginController::class, 'logout']);
});

Route::get('/users', [UsersController::class, 'index']);
