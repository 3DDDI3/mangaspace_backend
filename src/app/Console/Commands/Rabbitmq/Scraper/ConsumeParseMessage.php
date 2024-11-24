<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\Events\WS\Scraper\ParseEvent;
use App\Events\WS\Scraper\RequestSent;
use App\Events\WS\Scraper\ResponseReceived;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\FullTitleResource;
use App\Http\Resources\TitleResource;
use App\Models\Title;
use App\Models\User;
use App\View\Components\Admin\AccordionItem;
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
    protected $signature = 'rmq:scraper-consume-message {id} {job_id}';

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

        // Callback функция обработки сообщений
        $callback = function ($msg) use (&$isListening, $channel) {
            echo ' [x] Received ', $msg->body, "\n";

            $isListening = false;

            $title = new FullTitleResource(Title::query()->where(['slug' => json_decode($msg->body)])->first());

            // dd(new AccordionItem())

            broadcast(new ResponseReceived("message received {$msg->body} {$this->argument('job_id')}", $this->argument('id'), $title));

            $channel->basic_cancel('');
        };

        // Подписка на очередь
        $channel->basic_consume('response', 'scraper', false, true, false, false, $callback);

        $time = 60;

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
