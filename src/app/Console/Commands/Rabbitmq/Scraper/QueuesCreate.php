<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

class QueuesCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:queues-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание очередей и обменников, необходимых для работы rabbitmq';

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

        $channel->exchange_declare('scraper', 'direct', durable: true);
        $channel->exchange_declare('information', 'direct', durable: true);

        $args = new AMQPTable([
            'x-message-ttl' => (int)config('app.rmq_timeout') * 1000,
        ]);

        $channel->queue_declare('parseTitleRequest', auto_delete: false, durable: true, arguments: $args);
        $channel->queue_declare('parseTitleResponse', auto_delete: false, durable: true, arguments: $args);
        $channel->queue_declare('informationLog', auto_delete: false, durable: true, arguments: $args);
        $channel->queue_declare('errorLog', auto_delete: false, durable: true, arguments: $args);
        $channel->queue_declare('getChapterRequest', durable: true, auto_delete: false, arguments: $args);
        $channel->queue_declare('getChapterResponse', durable: true, auto_delete: false, arguments: $args);
        $channel->queue_declare('parseChapterRequest', durable: true, auto_delete: false, arguments: $args);
        $channel->queue_declare('parseChapterResponse', durable: true, auto_delete: false, arguments: $args);
        $channel->queue_bind('parseTitleRequest', 'scraper', 'parseTitleRequest');
        $channel->queue_bind('parseTitleResponse', 'scraper', 'parseTitleResponse');
        $channel->queue_bind('getChapterRequest', 'scraper', 'getChapterRequest');
        $channel->queue_bind('getChapterResponse', 'scraper', 'getChapterResponse');
        $channel->queue_bind('parseChapterRequest', 'scraper', 'parseChapterRequest');
        $channel->queue_bind('parseChapterResponse', 'scraper', 'parseChapterResponse');
        $channel->queue_bind('informationLog', 'information', 'informationLog');
        $channel->queue_bind('errorLog', 'information', 'errorLog');

        echo "Queues successefully created." . PHP_EOL;
    }
}
