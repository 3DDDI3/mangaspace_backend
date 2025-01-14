<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\Events\WS\Scraper\ParseEvent;
use App\Events\WS\Scraper\ParseTitlesEvent;
use App\Events\WS\Scraper\RequestSent;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class PublishParseTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:publish-parse-title-message {id} {job_id} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Публикация сообщения в очередь парсера';

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

        $msg = new AMQPMessage(
            $this->argument('message'),
            ['application_headers' => new AMQPTable(['id' => $this->argument('job_id')])]
        );

        $channel->basic_publish($msg, 'scraper', 'parseTitleRequest');
    }
}
