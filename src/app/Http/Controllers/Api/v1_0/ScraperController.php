<?php

namespace App\Http\Controllers\Api\v1_0;

use App\DTO\RequestDTO;
use App\DTO\TitleDTO;
use App\Events\NewEvent;
use App\Http\Controllers\Controller;
use App\Jobs\TestJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Throwable;

class ScraperController extends Controller
{
    public function parse(Request $request)
    {
        $requestDTO = new RequestDTO($request->pages, new TitleDTO('123', '321'));

        TestJob::dispatch($requestDTO)->onQueue('scraper');

        return response()->json(['message' => 'ok'], 200);
    }

    public function getCHapters(Request $request) {}
}
