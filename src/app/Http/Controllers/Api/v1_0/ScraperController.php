<?php

namespace App\Http\Controllers\Api\v1_0;

use App\Http\Controllers\Controller;
use App\Jobs\TestJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ScraperController extends Controller
{
    public function parse(Request $request)
    {
        $user = $request->user();


        TestJob::dispatch($user);

        
    }
}
