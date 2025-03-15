<?php

use App\Http\Controllers\Api\v1_0\AuthController;
use App\Http\Controllers\Api\v1_0\ChapterImageController;
use App\Http\Controllers\Api\v1_0\GenreController;
use App\Http\Controllers\Api\v1_0\PersonController;
use App\Http\Controllers\Api\v1_0\PersonImageController;
use App\Http\Controllers\Api\v1_0\ScraperController;
use App\Http\Controllers\Api\v1_0\TitleChapterController;
use App\Http\Controllers\Api\v1_0\TitleController;
use App\Http\Controllers\Api\v1_0\TitleCoverController;
use App\Http\Controllers\Api\v1_0\TitleGenreController;
use App\Http\Controllers\Api\v1_0\TitlePersonController;
use App\Http\Controllers\Api\v1_0\TitlePersonPhotoController;
use App\Http\Controllers\Api\v1_0\UserController;
use App\Http\Controllers\Api\v1_0\UserPermissionController;
use App\Http\Controllers\Api\v1_0\WebSocketController;
use App\Http\Resources\TitleResource;
use App\Models\Chapter;
use App\Models\Title;
use App\View\Components\Admin\Accordion;
use App\View\Components\Admin\AccordionItem;
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
        Route::post('titles', 'parseTitles');
        Route::get('chapters', 'getChapters');
        Route::post('chapters', 'parseChapters');
        Route::get('info', 'getInfo');
    });

Route::prefix('ws')
    ->controller(WebSocketController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('chapters', 'getChapters');
        Route::get('info', 'getInfo');
    });

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('titles', TitleController::class);
    Route::apiResource('titles.persons', TitlePersonController::class)->except(['index', 'show']);
    Route::apiResource('titles.genres', TitleGenreController::class)->except(['index', 'show']);
    Route::apiResource('titles.covers', TitleCoverController::class);
    Route::apiResource('titles.chapters', TitleChapterController::class);
    Route::apiResource('titles.chapters.images', ChapterImageController::class)->except(['show', 'index']);
});

Route::middleware('auth:sanctum')
    ->apiResource('genres', GenreController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('persons', PersonController::class);
    Route::apiResource('persons.images', PersonImageController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('users.permissions', UserPermissionController::class);
});
