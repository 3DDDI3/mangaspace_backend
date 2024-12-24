<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\DTO\ResponseDTO;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumeParseChapter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consume-parse-chapter';

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

        $callback = function ($msg) use (&$isListening, $channel) {
            $isListening = false;

            $response = json_decode($msg->body);

            $responseDTO = new ResponseDTO(
                new TitleDTO($response->titleDTO->url, $response->titleDTO->chapters),
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

        $channel->basic_consume('chapterResponse', 'chapterResponse', false, true, false, false, $callback);

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
