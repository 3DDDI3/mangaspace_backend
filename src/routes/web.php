<?php

use App\Events\WS\Scraper\ParseEvent;
use App\Http\Controllers\Api\v1_0\AuthController;
use App\Jobs\TestJob;
use App\Models\User;
use Illuminate\Console\Scheduling\Event;
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

Route::get('check', function () {
    $connection = new AMQPStreamConnection(
        config('rabbitmq.host'),
        config('rabbitmq.port'),
        config('rabbitmq.user'),
        config('rabbitmq.password')
    );
    $channel = $connection->channel();

    $channel->queue_declare('bye', false, false, false, false);

    $isListening = true;

    $callback = function ($msg) use (&$isListening, $channel) {
        echo ' [x] Received ', $msg->body, "\n";

        $headers = $msg->get('application_headers');

        foreach ($headers as $key => $value) {
            if ($key == "job_id") $jobid = $value[1];
        }

        dd($jobid);
        $user = new User(json_decode($this->argument('user'), true));

        $isListening = false;

        $channel->basic_cancel('');

        broadcast(new ParseEvent($user, "message received $jobid"));
    };

    $channel->basic_consume('bye', '', false, true, false, false, $callback);

    try {
        while ($isListening) {
            $channel->wait();
        }
    } catch (\Throwable $exception) {
        echo $exception->getMessage();
    }
});

Route::get('chat', function () {
    return view('chat');
});
Route::get('/chat/{friend}', function ($friend) {
    return view('chat', [
        'friend' => $friend
    ]);
});
