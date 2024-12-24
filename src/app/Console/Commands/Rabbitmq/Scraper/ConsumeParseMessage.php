<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\Events\WS\Scraper\ParseEvent;
use App\Events\WS\Scraper\RequestSent;
use App\Events\WS\Scraper\ResponseReceived;
use App\Http\Resources\FullTitleResource;
use App\Models\Title;
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
    protected $description = 'Прослушивание очереди парсера';

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

            broadcast(new ResponseReceived("message received {$msg->body}", $this->argument('id'), $title));

            $channel->basic_cancel('');

            // $title = Title::query()->find(3);

            // $accordionItem = new AccordionItem();
            // $accordion = new Accordion();

            // $chapters = Chapter::all();

            // $html = $accordion->render()->with([
            //     'id' => 'accordionFlushExample',
            //     'slot' => $accordionItem->render()->with([
            //         'objectType' => 'title',
            //         'object' => $title,
            //         'isOnlyChapter' => !empty($request->chapter) ? true : false,
            //         'accordionId' => 'accordionFlushExample',
            //         'slot' => $accordion->render()->with([
            //             'id' => 'accordionFlushExample1',
            //             'slot' => $accordionItem->render()->with([
            //                 'objectType' => 'chapter',
            //                 'object' => $chapters,
            //                 'isOnlyChapter' => !empty($request->chapter) ? true : false,
            //                 'accordionId' => 'accordionFlushExample1',
            //                 'slot' => null
            //             ]),
            //         ])
            //     ]),
            // ]);

        };

        // Подписка на очередь
        $channel->basic_consume('response', 'scraper', false, true, false, false, $callback);

        $time = config('app.rmq_timeout');

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
