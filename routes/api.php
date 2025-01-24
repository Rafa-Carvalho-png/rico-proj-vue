<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallsController;
use App\Http\Controllers\UsersController;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => '/users'], function () {
        Route::get('/get-online', [UsersController::class, 'getOnline']);
    });

    Route::group(['prefix' => '/calls'], function () {
        Route::post('/request', [CallsController::class, 'requestCall']);
        Route::post('/accept', [CallsController::class, 'acceptCall']);
        Route::post('/reject', [CallsController::class, 'rejectCall']);
    });

    Route::get('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user/calls', [CallsController::class, 'paginateUserCalls']);
});
