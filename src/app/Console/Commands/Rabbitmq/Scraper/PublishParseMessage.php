<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\Events\WS\Scraper\ParseEvent;
use App\Models\User;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class PublishParseMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:scraper-publish-message {user} {job_id?}';

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

        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World!', ['application_headers' => new AMQPTable(['job_id' => $this->argument('job_id')])]);
        $channel->basic_publish($msg, '', 'hello');
        // return;

        echo " [x] Sent 'Hello World!'\n";

        $user = new User(json_decode($this->argument('user'), true));

        $jobid = $this->argument('job_id');

        broadcast(new ParseEvent($user, "message sended $jobid"));
    }
}
