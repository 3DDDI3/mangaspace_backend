<?php

use App\DTO\RequestDTO;
use App\DTO\TitleDTO;
use App\Jobs\TestJob;
use App\Models\Chapter;
use App\Models\DeviceType;
use App\Models\Title;
use App\View\Components\Admin\Accordion;
use App\View\Components\Admin\AccordionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
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
    TestJob::dispatchSync($request->user());
})->middleware('auth:sanctum');

Route::get('chat', function () {
    return view('chat');
});
Route::get('/chat/{friend}', function ($friend) {
    return view('chat', [
        'friend' => $friend
    ]);
});
