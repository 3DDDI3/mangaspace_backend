<?php

use App\DTO\RequestDTO;
use App\DTO\ResponseDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Http\Resources\FullTitleResource;
use App\Jobs\TestJob;
use App\Models\Chapter;
use App\Models\DeviceType;
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

use function Symfony\Component\String\b;

Route::get('/', function () {
    // $tile = Title::find(1)->persons;
    return view('welcome');
});
Route::get('test', function () {
    $accordionItem = new AccordionItem();
    $accordion = new Accordion();

    $title = Title::all();

    $chapters = Chapter::all();

    $html = $accordion->render()->with([
        'id' => 'accordionFlushExample',
        'slot' => $accordionItem->render()->with([
            'objectType' => 'title',
            'object' => $title,
            'isOnlyChapter' => !empty($request->chapter) ? true : false,
            'accordionId' => 'accordionFlushExample',
            'slot' => $accordion->render()->with([
                'id' => 'accordionFlushExample1',
                'slot' => $accordionItem->render()->with([
                    'objectType' => 'chapter',
                    'object' => $chapters,
                    'isOnlyChapter' => !empty($request->chapter) ? true : false,
                    'accordionId' => 'accordionFlushExample1',
                    'slot' => null
                ]),
            ])
        ]),
    ]);

    return response($html);
});

Route::get('check', function (Request $request) {

    $item = new Item();

    $html = $item->render()->with([
        'id' => 1,
        'ariaLabel' => null,
        'value' => 1,
        'data' => [1, 23, 3]
    ]);

    return response($html->render());

    $json = '{"page":null,"titleDTO":{"url":"","chapters":[{"name":"https://remanga.org/manga/omniscient-reader/1016672","url":null,"number":"233"}]},"scraperDTO":{"action":"","engine":""},"isLast":true}';
    $response = json_decode($json);

    $responseDTO = new ResponseDTO(new TitleDTO($response->titleDTO->url, $response->titleDTO->chapters), new ScraperDTO($response->scraperDTO->action, $response->scraperDTO->engine));

    dd($responseDTO->titleDTO->chapterDTO[0]->name);

    // $title = new FullTitleResource(Title::query()->where(['slug' => $responseDTO->titleDTO->url])->first());
    $title = Title::query()->find(3);

    $accordionItem = new AccordionItem();
    $accordion = new Accordion();

    $chapters = Chapter::all();

    $html = $accordion->render()->with([
        'id' => 'accordionFlushExample',
        'slot' => $accordionItem->render()->with([
            'objectType' => 'title',
            'object' => $title,
            'isOnlyChapter' => !empty($request->chapter) ? true : false,
            'accordionId' => 'accordionFlushExample',
            'slot' => $accordion->render()->with([
                'id' => 'accordionFlushExample1',
                'slot' => $accordionItem->render()->with([
                    'objectType' => 'chapter',
                    'object' => $chapters,
                    'isOnlyChapter' => !empty($request->chapter) ? true : false,
                    'accordionId' => 'accordionFlushExample1',
                    'slot' => null
                ]),
            ])
        ]),
    ]);


    dd($html);
});

Route::get('chat', function () {
    return view('chat');
});
Route::get('/chat/{friend}', function ($friend) {
    return view('chat', [
        'friend' => $friend
    ]);
});
