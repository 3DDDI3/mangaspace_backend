<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\DTO\LogDTO;
use App\Events\WS\Scraper\GetErrorEvent;
use App\Events\WS\Scraper\GetLogEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;

class ConsumeError extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:consume-error-message {id}';

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
        $isListening = true;

        $callback = function ($msg) use (&$isListening, $channel, $connection) {
            $message = json_decode($msg->body);
            $logDTO = new LogDTO($message->message, $message->isLast);

            if (isset($logDTO->message))
                broadcast(new GetErrorEvent($this->argument('id'), $logDTO->message));

            if ($logDTO->isLast) {
                $isListening = false;
                Log::info('consumeError completed');
                $channel->basic_cancel('');
                $channel->close();
                $connection->close();
            }
        };

        $channel->basic_consume('errorLog', 'information', false, true, false, false, $callback);

        $time = config('app.rmq_timeout');

        try {
            while ($isListening) {
                $channel->wait(null, false, $time);
            }
        } catch (AMQPTimeoutException $e) {
            Log::error("Job не успел завершиться за " . $time . " cек.");
            $channel->close();
            $connection->close();
        } finally {
            $channel->close();
            $connection->close();
        }
    }
}
