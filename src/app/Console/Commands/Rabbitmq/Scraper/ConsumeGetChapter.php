<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\DTO\ChapterDTO;
use App\DTO\ResponseDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Events\WS\Scraper\GetChapterResponseReceived;
use App\Events\WS\Scraper\GetChapters;
use App\Events\WS\Scraper\GetChaptersEvent;
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

        Log::info("ConsumeGetChapter started" . PHP_EOL);

        $callback = function ($msg) use (&$isListening, $channel, $connection) {
            $response = json_decode($msg->body);

            $responseDTO = new ResponseDTO(
                new TitleDTO($response->titleDTO?->url, $response->titleDTO?->name, $response->titleDTO?->chapterDTO),
                new ScraperDTO($response->scraperDTO->action, $response->scraperDTO->engine)
            );

            if ($responseDTO->titleDTO->chapterDTO[0]->isLast) {
                $isListening = false;
                Log::info('ConsumeGetChapter completed');
                $channel->basic_cancel('');
                $channel->close();
                $connection->close();
            }

            $list = new ItemsList();
            $item = new Item();

            $name = !empty($responseDTO->titleDTO->chapterDTO[0]->name) ? ": {$responseDTO->titleDTO->chapterDTO[0]->name}" : " ";

            $html = $item->render()->with([
                'id' => $responseDTO->titleDTO->chapterDTO[0]->number,
                'value' => "Том " . $responseDTO->titleDTO->chapterDTO[0]->volume . ".Глава " . $responseDTO->titleDTO->chapterDTO[0]->number . $name . " (переводчик " . $responseDTO->titleDTO->chapterDTO[0]->translator . ")",
                'ariaLabel' => null,
                'data' => [
                    'url' => $responseDTO->titleDTO->chapterDTO[0]->url,
                    'number' => $responseDTO->titleDTO->chapterDTO[0]->number
                ],
            ]);

            broadcast(new GetChaptersEvent(
                "message received {$this->argument('job_id')}",
                $this->argument('id'),
                $responseDTO->titleDTO->chapterDTO[0]->isLast,
                $html->render()
            ));
        };

        $channel->basic_consume('getChapterResponse', 'scraper', false, true, false, false, $callback);

        $time = config('app.rmq_timeout');

        try {
            while ($isListening) {
                $channel->wait(null, false, $time);
            }
        } catch (AMQPTimeoutException $e) {
            Log::error("Job не успел завершиться за " . $time . " cек.");
            $channel->close();
            $connection->close();
        } finally {
            $channel->close();
            $connection->close();
        }
    }
}
