<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Http\Resources\TitleResource;
use App\Models\Chapter;
use App\Models\Title;
use App\View\Components\Admin\Accordion;
use App\View\Components\Admin\AccordionItem;
use Illuminate\Http\Request;

class WebSocketController extends Controller
{
    public function getChapters(Request $request) {}

    /**
     * Отправка данных по websocket
     *
     * @param Request $request
     * @return void
     */
    public function getInfo(Request $request)
    {
        if (request()->acceptsJson())
            return TitleResource::collection(Title::all());

        if (request()->acceptsHtml()) {
            $accordionItem = new AccordionItem();
            $accordion = new Accordion();

            $titles = empty($request->title) ? Title::all() : Title::query()->where(['slug' => $request->title])->get();

            foreach ($titles as $title) {
                $html[] = $accordion->render()->with([
                    'id' => 'accordionFlushExample',
                    'slot' => $accordionItem->render()->with([
                        'objectType' => 'title',
                        'object' => $title,
                        'isOnlyChapter' => empty($request->chapter) ? false : true,
                        'accordionId' => 'accordionFlushExample',
                        'slot' => $accordion->render()->with([
                            'id' => 'accordionFlushExample1',
                            'slot' => $accordionItem->render()->with([
                                'objectType' => 'chapter',
                                'object' => !empty($request->chapter) ? $title->chapters()->where(['number' => $request->chapter])->get() : $title->chapters,
                                'isOnlyChapter' => empty($request->chapter) ? false : true,
                                'accordionId' => 'accordionFlushExample1',
                                'slot' => null
                            ]),
                        ])
                    ]),
                ]);
            }

            return response(implode("", $html));
        }
    }
}
