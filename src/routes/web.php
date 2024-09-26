<?php

use App\Http\Controllers\Api\v1_0\AuthController;
use App\Jobs\TestJob;
use App\Models\User;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('test', function () {
    // Redis::set('name', 'taylor');
    Artisan::call('rmq:send-message');
});

Route::get('/chat/{friend}', function ($friend) {
    return view('chat', [
        'friend' => $friend
    ]);
});
