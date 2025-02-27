<?php

namespace App\Console\Commands\Rabbitmq\Scraper;

use App\DTO\ResponseDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Events\WS\Scraper\ParseChaptersEvent;
use App\Http\Resources\FullTitleChapterResource;
use App\Http\Resources\FullTitleResource;
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

class ConsumeParseChapter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rmq:consume-parse-chapter-message {id} {job_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсинг глав конкретного тайтла';

    /** 
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("ConsumeParseChapter finished" . PHP_EOL);

        $connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password')
        );
        $channel = $connection->channel();

        $isListening = true;

        $callback = function ($msg) use (&$isListening, $channel) {
            $response = json_decode($msg->body);

            $responseDTO = new ResponseDTO(
                new TitleDTO($response->titleDTO->url, $response->titleDTO->name, $response->titleDTO->chapterDTO),
                new ScraperDTO($response->scraperDTO->action, $response->scraperDTO->engine)
            );

            $headers = $msg->get_properties();
            if ($headers['application_headers']['job_id'] == $this->argument('job_id') && $responseDTO->titleDTO->chapterDTO[0]->isLast)
                $isListening = false;

            $title = new FullTitleResource(Title::query()->where(['ru_name' => $responseDTO->titleDTO->name])->first());

            $accordionItem = new AccordionItem();
            $accordion = new Accordion();

            if ($responseDTO->titleDTO->chapterDTO[0]->isFirst) {

                $html = $accordion->render()->with([
                    'id' => 'accordionFlushExample',
                    'slot' => $accordionItem->render()->with([
                        'objectType' => 'title',
                        'object' => $title,
                        'isOnlyChapter' => false,
                        'accordionId' => 'accordionFlushExample',
                        'slot' => $accordion->render()->with([
                            'id' => 'accordionFlushExample1',
                            'slot' => $accordionItem->render()->with([
                                'objectType' => 'chapter',
                                'object' => [],
                                'isOnlyChapter' => false,
                                'accordionId' => 'accordionFlushExample1',
                                'slot' => null
                            ]),
                        ])
                    ]),
                ]);

                broadcast(new ParseChaptersEvent((int)$this->argument('id'), $responseDTO->titleDTO->chapterDTO[0], $html));

                $chapter = Chapter::query()
                    ->where(['number' => $responseDTO->titleDTO->chapterDTO[0]->number])
                    ->first();

                $chapterImage = ChapterImage::query()
                    ->where([
                        'chapter_id' => $chapter->id,
                        'person_id' => Person::query()->where(['name' => $responseDTO->titleDTO->chapterDTO[0]->translator])?->first()?->id
                    ])->first();

                $chapterImageResource = new FullTitleChapterResource($chapterImage);

                $responseDTO->titleDTO->chapterDTO[0]->isFirst = false;

                broadcast(new ParseChaptersEvent((int)$this->argument('id'), $responseDTO->titleDTO->chapterDTO[0], obj: $chapterImageResource));
            } else {

                $chapter = Chapter::query()
                    ->where(['number' => $responseDTO->titleDTO->chapterDTO[0]->number])
                    ->first();

                $chapterImage = ChapterImage::query()
                    ->where([
                        'chapter_id' => $chapter->id,
                        'person_id' => Person::query()->where(['name' => $responseDTO->titleDTO->chapterDTO[0]->translator])?->first()?->id
                    ])->first();

                $chapterImageResource = new FullTitleChapterResource($chapterImage);

                broadcast(new ParseChaptersEvent((int)$this->argument('id'), $responseDTO->titleDTO->chapterDTO[0], obj: $chapterImageResource));
            }

        };

        // Подписка на очередь
        $channel->basic_consume('parseChapterResponse', 'scraper', false, true, false, false, $callback);

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
