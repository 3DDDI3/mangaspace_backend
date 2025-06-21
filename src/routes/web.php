<?php

use App\DTO\ResponseDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Http\Resources\FullTitleChapterResource;
use App\Http\Resources\FullTitleResource;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Person;
use App\Models\ReleaseFormat;
use App\Models\Title;
use App\Models\UserPermission;
use App\Services\ImageStringService;
use App\View\Components\Admin\Accordion;
use App\View\Components\Admin\AccordionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\type;
use function Symfony\Component\String\b;

Route::get('/', function () {
    // $tile = Title::find(1)->persons;
    // dd(new ResponseDTO(new TitleDTO(), new ScraperDTO("", "")));
    // return view('welcome');
    // $rf = ReleaseFormat::query()->find(1);
    // $model = $rf->replicate();
    // $model->setConnection('mysql')->save();

    // // ReleaseFormat::on('mysql');
    // dd(ReleaseFormat::all());

    // foreach (Storage::disk('shared')->allDirectories(Chapter::query()->first()->path) as $directory) {
    //     Storage::disk('shared')->deleteDirectory($directory);
    // }

    // Storage::disk('shared')->deleteDirectory(Chapter::query()->first()->path);
});
Route::get('/test1', function () {

    $chapter = Chapter::query()
        ->find(14);

    $chapterImage = $chapter->images()
        ->first();

    $imageCollection = ImageStringService::deleteImage($chapterImage->extensions, '1.jpg');
});

Route::get('test', function () {
    //     $json = '{"pages":null,"titleDTO":{"url":null,"name":"Всеведущий читатель","chapterDTO":[{"url":null,"name":"Всеведущий читатель","number":"235","isFirst":true,"isLast":true}]},"scraperDTO":{"action":"parseChapters","engine":"remanga"}}';

    //     $response = json_decode($json);

    //     $responseDTO = new ResponseDTO(
    //         new TitleDTO($response->titleDTO->url, $response->titleDTO->name, $response->titleDTO->chapterDTO),
    //         new ScraperDTO($response->scraperDTO->action, $response->scraperDTO->engine)
    //     );

    //     $accordionItem = new AccordionItem();
    //     $accordion = new Accordion();

    //     $chapter = Chapter::query()
    //         ->where(['number' => $responseDTO->titleDTO->chapterDTO[0]->number])
    //         ->get();

    //     // $html = $accordionItem->render()->with([
    //     //     'objectType' => 'chapter',
    //     //     'object' => $chapter,
    //     //     'isOnlyChapter' => true,
    //     //     'accordionId' => 'accordionFlushExample1',
    //     //     'slot' => null,
    //     // ]);


    $accordionItem = new AccordionItem();
    $accordion = new Accordion();

    $title = new FullTitleResource(Title::query()->where(['id' => 19])->first());

    $chapter = Chapter::query()
        ->where(['number' => 67])
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
