<?php

namespace App\Console\Commands\Rabbitmq;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:send-message';

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
        $connection = new AMQPStreamConnection(config('rabbitmq.host'), config('rabbitmq.port'), config('rabbitmq.user'), config('rabbitmq.password'));
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $headers = new \PhpAmqpLib\Wire\AMQPTable([
            'your_header_key' => 'your_header_value',
            'another_header' => 'another_value'
        ]);

        $msg = new AMQPMessage('Hello World!', ['application_headers' => $headers]);
        $channel->basic_publish($msg, 'test', 'request');

        echo " [x] Sent 'Hello World!'\n";
    }
}
