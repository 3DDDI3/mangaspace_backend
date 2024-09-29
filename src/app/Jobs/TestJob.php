<?php

namespace App\Jobs;

use App\Events\WS\Scraper\ParseEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Throwable;

class TestJob implements ShouldQueue
{
    use Queueable;

    private $user;

    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call('rmq:scraper-publish-message ' . addslashes($this->user->toJson()) . " " . $this->job->uuid());

        Artisan::call('rmq:scraper-consume-message ' . addslashes($this->user->toJson()) . " " . $this->job->uuid() . " --time=$this->timeout");
    }

    public function failed(Throwable $e): void
    {
        Log::error($e->getMessage());
    }
}
