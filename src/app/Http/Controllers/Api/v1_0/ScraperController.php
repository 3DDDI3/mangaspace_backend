<?php

namespace App\Http\Controllers\Api\v1_0;

use App\DTO\RequestDTO;
use App\DTO\TitleDTO;
use App\Http\Controllers\Controller;
use App\Jobs\TestJob;
use App\Services\RequestStringService;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    public function parse(Request $request)
    {
        $requestDTO = new RequestDTO(
            (new RequestStringService())->parseString($request->pages),
            new TitleDTO('123', [1, 2])
        );

        TestJob::dispatch(json_encode($requestDTO))->onQueue('scraper');

        return response()->json(['message' => 'ok'], 200);
    }

    public function getCHapters(Request $request) {}
}
