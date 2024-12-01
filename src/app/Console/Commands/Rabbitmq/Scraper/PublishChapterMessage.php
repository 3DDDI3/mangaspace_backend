<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use Illuminate\Console\Command;

class PublishChapterMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:publish-chapter-message';

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

        $msg = new AMQPMessage(
            $this->argument('message'),
            ['application_headers' => new AMQPTable(['id' => $this->argument('job_id')])]
        );

        $channel->basic_publish($msg, 'scraper', 'chapter-request');

        broadcast(new RequestSent("message {$this->argument('message')} sended", $this->argument('id')));
    }
}
