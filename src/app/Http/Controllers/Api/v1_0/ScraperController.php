<?php

namespace App\Http\Controllers\Api\v1_0;

use App\DTO\RequestDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\TitleResource;
use App\Jobs\Scraper\ParseChapterJob;
use App\Jobs\Scraper\ParseJob;
use App\Models\Chapter;
use App\Models\Title;
use App\Services\RequestStringService;
use App\View\Components\Admin\Accordion;
use App\View\Components\Admin\AccordionItem;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    public function parse(Request $request)
    {
        $requestDTO = new RequestDTO(
            (new RequestStringService())->parseString($request->pages),
            new TitleDTO('123', [1, 2])
        );

        ParseJob::dispatch(json_encode($requestDTO), $request->user()->id)->onQueue('scraper');
        // ParseChapterJob::dispatch(json_encode("MESSAGE"), $request->user()->id)->onQueue('scraper');

        return response()->json(['message' => 'ok'], 200);
    }
    public function parseChapters(Request $request)
    {
        $requestDTO = new RequestDTO(titleDTO: new TitleDTO('https://' . $request->input('url')), scraperDTO: new ScraperDTO("chapters-parse", "remanga"));

        ParseChapterJob::dispatch(json_encode($requestDTO), $request->user()->id)->onQueue('scraper');
    }
}
