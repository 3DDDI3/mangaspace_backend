<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

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
        $channel->exchange_declare('information', 'direct');
        $channel->queue_declare('request', auto_delete: false, durable: true);
        $channel->queue_declare('response', auto_delete: false, durable: true);
        $channel->queue_declare('log', durable: true);
        $channel->queue_declare('error', durable: true);
        $channel->queue_declare('getChapterRequest', durable: true, auto_delete: false);
        $channel->queue_declare('getChapterResponse', durable: true, auto_delete: false);
        $channel->queue_declare('parseChapterRequest', durable: true, auto_delete: false);
        $channel->queue_declare('parseChapterResponse', durable: true, auto_delete: false);
        $channel->queue_bind('request', 'scraper', 'request');
        $channel->queue_bind('response', 'scraper', 'response');
        $channel->queue_bind('getChapterRequest', 'scraper', 'getChapterRequest');
        $channel->queue_bind('getChapterResponse', 'scraper', 'getChapterResponse');
        $channel->queue_bind('parseChapterRequest', 'scraper', 'parseChapterRequest');
        $channel->queue_bind('parseChapterResponse', 'scraper', 'parseChapterResponse');
        $channel->queue_bind('error', 'information', 'error');
        $channel->queue_bind('log', 'information', 'log');
    }
}
