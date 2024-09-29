<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\Events\WS\Scraper\ParseEvent;
use App\Models\User;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumeParseMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:scraper-consume-message {user} {job_id?}';

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

        $channel->queue_declare('bye', false, false, false, false);

        $isListening = true;

        $callback = function ($msg) use (&$isListening, $channel) {
            echo ' [x] Received ', $msg->body, "\n";

            $headers = $msg->get('application_headers');

            foreach ($headers as $key => $value) {
                if ($key == "job_id") $jobid = $value[1];
            }

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
    }
}
