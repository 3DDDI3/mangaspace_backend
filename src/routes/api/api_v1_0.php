<?php

use App\Http\Controllers\Api\v1_0\AuthController;
use App\Http\Controllers\Api\v1_0\ScraperController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\TitleGenreController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('signin', 'signin');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
        Route::get('check', 'check')->middleware('auth:sanctum');
    });

Route::prefix('scraper')
    ->controller(ScraperController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('parse', 'parse');
        Route::post('get-chapters', 'getChapters');
    });

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('titles', TitleController::class);
    Route::apiResource('titles.persons', PersonController::class);
    Route::apiResource('titles.genres', TitleGenreController::class);
    Route::apiResource('titles.chapters', ChapterController::class);
    Route::apiResource('titles.chapters.images', ImageController::class);
});
