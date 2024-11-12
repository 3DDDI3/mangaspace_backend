<?php

use App\Http\Controllers\Api\v1_0\AuthController;
use App\Http\Controllers\Api\v1_0\ChapterImageController;
use App\Http\Controllers\Api\v1_0\GenreController;
use App\Http\Controllers\Api\v1_0\PersonController;
use App\Http\Controllers\Api\v1_0\PersonImageController;
use App\Http\Controllers\Api\v1_0\ScraperController;
use App\Http\Controllers\Api\v1_0\TitleChapterController;
use App\Http\Controllers\Api\v1_0\TitleController;
use App\Http\Controllers\Api\v1_0\TitleGenreController;
use App\Http\Controllers\Api\v1_0\TitlePersonController;
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
    Route::apiResource('titles.persons', TitlePersonController::class);
    Route::apiResource('titles.genres', TitleGenreController::class);
    Route::apiResource('titles.chapters', TitleChapterController::class);
    Route::apiResource('titles.chapters.images', ChapterImageController::class);
});

Route::middleware('auth:sanctum')->apiResource('genres', GenreController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('persons', PersonController::class);
    Route::apiResource('persons.images', PersonImageController::class);
});
