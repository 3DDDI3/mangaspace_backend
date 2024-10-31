<?php

namespace App\Jobs;

use App\DTO\RequestDTO;
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

    public $timeout = 120;

    private string $message;

    /**
     * Create a new job instance.
     */
    public function __construct(string $requestDTO)
    {
        $this->message = $requestDTO;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call("rmq:scraper-publish-message " . addslashes($this->message));
        // Artisan::call('rmq:scraper-consume-message');
    }
}
