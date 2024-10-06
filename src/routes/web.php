<?php

use App\Jobs\TestJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use WhichBrowser\Model\Os;
use WhichBrowser\Parser;

use function Symfony\Component\String\b;

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
