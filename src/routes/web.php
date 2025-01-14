<?php

use App\DTO\RequestDTO;
use App\DTO\ResponseDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Http\Resources\FullTitleChapterResource;
use App\Http\Resources\FullTitleResource;
use App\Http\Resources\PersonResource;
use App\Jobs\TestJob;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\DeviceType;
use App\Models\Person;
use App\Models\Title;
use App\View\Components\Admin\Accordion;
use App\View\Components\Admin\AccordionItem;
use App\View\Components\Admin\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use WhichBrowser\Model\Os;
use WhichBrowser\Parser;

use function PHPSTORM_META\type;
use function Symfony\Component\String\b;

Route::get('/', function () {
    // $tile = Title::find(1)->persons;
    // dd(new ResponseDTO(new TitleDTO(), new ScraperDTO("", "")));
    // return view('welcome');
});
Route::get('test', function () {
    $json = '{"pages":null,"titleDTO":{"url":null,"name":"Всеведущий читатель","chapterDTO":[{"url":null,"name":"Всеведущий читатель","number":"235","isFirst":true,"isLast":true}]},"scraperDTO":{"action":"parseChapters","engine":"remanga"}}';

    $response = json_decode($json);

    $responseDTO = new ResponseDTO(
        new TitleDTO($response->titleDTO->url, $response->titleDTO->name, $response->titleDTO->chapterDTO),
        new ScraperDTO($response->scraperDTO->action, $response->scraperDTO->engine)
    );

    $accordionItem = new AccordionItem();
    $accordion = new Accordion();

    $chapter = Chapter::query()
        ->where(['number' => $responseDTO->titleDTO->chapterDTO[0]->number])
        ->get();

    // $html = $accordionItem->render()->with([
    //     'objectType' => 'chapter',
    //     'object' => $chapter,
    //     'isOnlyChapter' => true,
    //     'accordionId' => 'accordionFlushExample1',
    //     'slot' => null,
    // ]);


    $accordionItem = new AccordionItem();
    $accordion = new Accordion();

    $title = new FullTitleResource(Title::query()->where(['id' => 2])->first());

    $chapter = Chapter::query()
        ->where(['number' => 234])
        ->get();

    // $chapterImage = ChapterImage::query()
    //     ->where([
    //         'chapter_id' => $chapter->id,
    //         'person_id' => Person::query()->where(['name' => 'Никайто'])->first()->id
    //     ])
    //     ->first();

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
                    'object' => $chapter,
                    'isOnlyChapter' => false,
                    'accordionId' => 'accordionFlushExample1',
                    'slot' => null
                ]),
            ])
        ]),
    ]);

    return response($html);
});

Route::get('check', function (Request $request) {
    $string = "1..5,10,15,11..14";
    $substr = explode(",", $string);
    $pages = [];

    for ($i = 0; $i < count($substr); $i++) {
        if (preg_match("/(\d+)\.{2}(\d+)/", $substr[$i], $matches)) {
            for ($j = (int)$matches[1]; $j <= (int)$matches[2]; $j++) {
                if ($j == $matches[2])
                    $pages[] = intval($j);
                else
                    $pages[] = $j;
            }
        } else {
            $pages[] = (int)$substr[$i];
        }
    }

    natsort($pages);

    $pages = array_values($pages);
    dd($pages);
});

Route::get('chat', function () {
    dd(config('app.rmq_timeout'));
});
Route::get('/chat/{friend}', function ($friend) {
    return view('chat', [
        'friend' => $friend
    ]);
});
