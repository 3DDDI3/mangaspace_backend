<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\DTO\ResponseDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Events\WS\Scraper\ChapterResponseReceived;
use App\Events\WS\Scraper\ResponseReceived;
use App\Http\Resources\FullTitleResource;
use App\Models\Chapter;
use App\Models\Title;
use App\View\Components\Admin\Accordion;
use App\View\Components\Admin\AccordionItem;
use App\View\Components\Admin\Item;
use App\View\Components\Admin\ItemsList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;

class ConsumeChapterMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:consume-chapter-message {id} {job_id}';

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

            $response = json_decode($msg->body);

            $responseDTO = new ResponseDTO(new TitleDTO($response->titleDTO->url, $response->titleDTO->chapters), new ScraperDTO($response->scraperDTO->action, $response->scraperDTO->engine));

            $list = new ItemsList();
            $item = new Item();

            $html = $item->render()->with([
                'id' => 1,
                'value' => $responseDTO->titleDTO->chapterDTO[0]->name,
                'ariaLabel' => null,
                'data' => ['url' => $responseDTO->titleDTO->chapterDTO[0]->name, 'number' => $responseDTO->titleDTO->chapterDTO[0]->number],
            ]);

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

            // {"page":null,"titleDTO":{"url":"","chapters":[{"name":"https://remanga.org/manga/omniscient-reader/1016672","url":null,"number":"233"}]},"scraperDTO":{"action":"","engine":""},"isLast":true}

            broadcast(new ChapterResponseReceived("message received {$this->argument('job_id')}", $this->argument('id'), $html->render()));

            $channel->basic_cancel('');
        };

        // Подписка на очередь
        $channel->basic_consume('chapterResponse', 'chapterResponse', false, true, false, false, $callback);

        $time = 300;

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
