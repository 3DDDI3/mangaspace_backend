<?php

use App\DTO\RequestDTO;
use App\DTO\TitleDTO;
use App\Jobs\TestJob;
use App\Models\DeviceType;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use WhichBrowser\Model\Os;
use WhichBrowser\Parser;

use function Symfony\Component\String\b;

Route::get('/', function () {
    $tile = Title::find(1)->persons;
    return view('welcome', $tile);
});
Route::get('test', function () {});

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
