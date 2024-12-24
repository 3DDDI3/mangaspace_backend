<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\Events\WS\Scraper\GetChapterRequestSent;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class PublishGetChapter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:publish-get-chapter-message {id} {job_id} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправка сообщения в rmq для получение списка найденных глав у конкретного тайтла';

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

        $channel->basic_publish($msg, 'scraper', 'getChapterRequest');

        // broadcast(new GetChapterRequestSent("message {$this->argument('message')} sended with job=" . $this->argument('job_id'), $this->argument('id')));
    }
}
