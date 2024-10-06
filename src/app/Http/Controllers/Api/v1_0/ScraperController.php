<?php

namespace App\Http\Controllers\Api\v1_0;

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
        $user = $request->user();

        // dd(Redis::connection('scraper')->zrevrange('session_queues:scraper', 0, -1, 'WITHSCORES'));

        TestJob::dispatch($user)->onQueue('scraper');
    }
}
