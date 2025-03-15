<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\DTO\ResponseDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Events\WS\Scraper\ParseTitlesEvent;
use App\Http\Resources\FullTitleChapterResource;
use App\Http\Resources\TitleResource;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Person;
use App\Models\Title;
use App\View\Components\Admin\Accordion;
use App\View\Components\Admin\AccordionItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;

class ConsumeParseTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:consume-parse-title-message {id} {job_id}';

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

            $response = json_decode($msg->body);

            $responseDTO = new ResponseDTO(
                new TitleDTO($response->titleDTO->url, $response->titleDTO->name, $response->titleDTO->chapterDTO),
                new ScraperDTO($response->scraperDTO->action, $response->scraperDTO->engine)
            );

            $headers = $msg->get_properties();

            if ($headers['application_headers']['job_id'] == $this->argument('job_id') && count($responseDTO->titleDTO->chapterDTO) > 0 && $responseDTO->titleDTO->chapterDTO[0]->isLast) {
                $isListening = false;
                Log::info('parseTitle completed');
                $channel->basic_cancel('');
            }

            $title = new TitleResource(Title::query()->where(['ru_name' => $responseDTO->titleDTO->name])->first());

            if ($responseDTO->titleDTO->chapterDTO[0]->isFirst)
                broadcast(new ParseTitlesEvent((int)$this->argument('id'), $title->id, $responseDTO->titleDTO, $title));

            $chapter = Chapter::query()
                ->where(['number' => $responseDTO->titleDTO->chapterDTO[0]->number])
                ->first();

            $chapterImage = ChapterImage::query()
                ->where([
                    'chapter_id' => $chapter->id,
                    'person_id' => Person::query()->where(['name' => $responseDTO->titleDTO->chapterDTO[0]->translator])?->first()?->id
                ])->first();

            $chapterImageResource = new FullTitleChapterResource($chapterImage);
            broadcast(new ParseTitlesEvent((int)$this->argument('id'), $title->id, $responseDTO->titleDTO, $chapterImageResource));
        };

        // Подписка на очередь
        $channel->basic_consume('parseTitleResponse', 'scraper', false, true, false, false, $callback);

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
