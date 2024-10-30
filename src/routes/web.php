<?php

use App\DTO\RequestDTO;
use App\DTO\TitleDTO;
use App\Jobs\TestJob;
use App\Models\DeviceType;
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
    dd(new RequestDTO('asd', new TitleDTO('123', '321')));
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
