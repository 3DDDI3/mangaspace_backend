<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\DTO\ResponseDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Events\WS\Scraper\GetChapterResponseReceived;
use App\View\Components\Admin\Item;
use App\View\Components\Admin\ItemsList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;

class ConsumeGetChapter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:consume-get-chapter-message {id} {job_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение списка найденных глав из rmq для конкретного тайтла';

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

        $callback = function ($msg) use (&$isListening, $channel) {
            $isListening = false;

            $response = json_decode($msg->body);

            $responseDTO = new ResponseDTO(
                new TitleDTO($response->titleDTO->url, $response->titleDTO->chapterDTO),
                new ScraperDTO($response->scraperDTO->action, $response->scraperDTO->engine)
            );

            $list = new ItemsList();
            $item = new Item();

            $html = $item->render()->with([
                'id' =>  $responseDTO->titleDTO->chapterDTO[0]->number,
                'value' => "Глава " . $responseDTO->titleDTO->chapterDTO[0]->number . $responseDTO->titleDTO->chapterDTO[0]->name,
                'ariaLabel' => null,
                'data' => [
                    'url' => $responseDTO->titleDTO->chapterDTO[0]->name,
                    'number' => $responseDTO->titleDTO->chapterDTO[0]->number
                ],
            ]);

            broadcast(new GetChapterResponseReceived(
                "message received {$this->argument('job_id')}",
                $this->argument('id'),
                $responseDTO->titleDTO->chapterDTO[0]->isLast,
                $html->render()
            ));

            $channel->basic_cancel('');
        };

        $channel->basic_consume('getChapterResponse', 'getChapterResponse', false, true, false, false, $callback);

        $time = config('app.rmq_timeout');

        try {
            while ($isListening) {
                $channel->wait(null, false, $time);
            }
        } catch (AMQPTimeoutException $e) {
            Log::error("Job не успел завершиться за " . $time . " cек.");
        } finally {
            $channel->close();
            $connection->close();
        }
    }
}
