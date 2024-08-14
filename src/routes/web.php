<?php

use App\Http\Controllers\Api\v1_0\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('test', function () {
    Redis::set('name', 'taylor');
});

Route::get('/chat/{friend}', function ($friend) {
    return view('chat', [
        'friend' => $friend
    ]);
});
