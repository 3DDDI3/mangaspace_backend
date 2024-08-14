<?php

use App\Http\Controllers\Api\v1_0\AuthController;
use App\Http\Controllers\Api\v1_0\PostController;
use Illuminate\Support\Facades\Route;

Route::apiResource('post', PostController::class);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('signin', 'signin');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::get('check', 'check')->middleware('auth:sanctum');
});
