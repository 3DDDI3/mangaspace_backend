<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\Events\WS\Scraper\ParseEvent;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;

class ConsumeParseMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:scraper-consume-message {user} {job_id?} {--time=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password')
        );
        $channel = $connection->channel();

        // Объявление очереди
        $channel->queue_declare('bye', false, false, false, false);

        $isListening = true;
        $jobid = "";

        // Callback функция обработки сообщений
        $callback = function ($msg) use (&$isListening, $channel, &$jobid) {
            echo ' [x] Received ', $msg->body, "\n";

            $headers = $msg->get('application_headers');

            if ($headers) {
                foreach ($headers as $key => $value) {
                    if ($key == "job_id") $jobid = $value[1];
                }
            }

            $user = new User(json_decode($this->argument('user'), true));

            $isListening = false;

            broadcast(new ParseEvent($user, "message received $jobid"));

            $channel->basic_cancel('');
        };

        // Подписка на очередь
        $channel->basic_consume('bye', '', false, true, false, false, $callback);

        $time = $this->option('time')[0] - 5;

        try {
            while ($isListening) {
                $channel->wait(null, false, $time);
            }
        } catch (AMQPTimeoutException $e) {
            Log::error("Job не успел завершиться за " . $time . " cек.");
        } finally {
            // Закрытие канала и соединения
            $channel->close();
            $connection->close();
        }
    }
}
