<?php

use App\Events\WS\Scraper\ParseEvent;
use App\Http\Controllers\Api\v1_0\AuthController;
use App\Jobs\TestJob;
use App\Models\User;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use PhpAmqpLib\Connection\AMQPStreamConnection;

Route::get('/', function () {
    return view('welcome');
});
Route::get('test', function () {
    // // Redis::set('name', 'taylor');
    // $user = (new User())->fill(['name' => 'asd', 'email' => 'asd', 'password' => 'asd'])->toJson();
    // // dd($user);
    // Artisan::call("rmq:send-message --user=$user");
    // broadcast(new ParseEvent($user, 'message sended'));
});

Route::get('check', function (Request $request) {
    TestJob::dispatchSync($request->user());
})->middleware('auth:sanctum');

Route::get('chat', function () {
    return view('chat');
});
Route::get('/chat/{friend}', function ($friend) {
    return view('chat', [
        'friend' => $friend
    ]);
});
