<?php

namespace App\Http\Controllers\Api\v1_0;

use App\DTO\ChapterDTO;
use App\DTO\RequestDTO;
use App\DTO\ScraperDTO;
use App\DTO\TitleDTO;
use App\Http\Controllers\Controller;
use App\Jobs\Scraper\ErrorLogJob;
use App\Jobs\Scraper\GetChapterJob;
use App\Jobs\Scraper\InformationLogJob;
use App\Jobs\Scraper\ParseChapterJob;
use App\Jobs\Scraper\ParseJob;
use App\Jobs\Scraper\ParseTitleJob;
use App\Services\RequestStringService;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    /**
     * Парсинг тайтлов на определенных страницах
     *
     * @param Request $request
     * @return void
     */
    public function parseTitles(Request $request)
    {
        $requestDTO = new RequestDTO(
            new TitleDTO(),
            new ScraperDTO($request->params['action'], $request->params['engine']),
            (new RequestStringService())->parseString($request->params['pages'])
        );

        if (!$requestDTO)
            return response([], 400);

        ParseTitleJob::dispatch(json_encode($requestDTO), $request->user()->id)->onQueue('scraper');
        InformationLogJob::dispatch($request->user()->id)->onQueue('scraper');
        ErrorLogJob::dispatch($request->user()->id)->onQueue('scraper');

        return response([], 200);
    }

    /**
     * Получение списка глав для парсинга
     *
     * @param Request $request
     * @return void
     */
    public function getChapters(Request $request)
    {
        $requestDTO = new RequestDTO(
            titleDTO: new TitleDTO($request->input('url')),
            scraperDTO: new ScraperDTO($request->action, $request->engine)
        );

        if (!$request)
            return response([], 400);

        GetChapterJob::dispatch(json_encode($requestDTO), $request->user()->id)->onQueue('scraper');
        InformationLogJob::dispatch($request->user()->id)->onQueue('scraper');
        ErrorLogJob::dispatch($request->user()->id)->onQueue('scraper');

        return response([], 200);
    }

    /**
     * Парсинг глав
     *
     * @param Request $request
     * @return void
     */
    public function parseChapters(Request $request)
    {
        foreach ($request->params['chapters'] as $chapter) {
            $chapters[] = new ChapterDTO($chapter);
        }

        $requestDTO = new RequestDTO(
            new TitleDTO(chapterDTO: $chapters),
            new ScraperDTO($request->params['action'], $request->params['engine'])
        );

        if (!$requestDTO)
            return response([], 400);

        ParseChapterJob::dispatch(json_encode($requestDTO), $request->user()->id)->onQueue('scraper');
        InformationLogJob::dispatch($request->user()->id)->onQueue('scraper');
        ErrorLogJob::dispatch($request->user()->id)->onQueue('scraper');

        return response([], 200);
    }
}
