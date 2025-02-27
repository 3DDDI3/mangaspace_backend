<?php

namespace App\Jobs\Scraper;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class ErrorLogJob implements ShouldQueue
{
    use Queueable;

    public $timeout;

    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $id)
    {
        $this->timeout = config('app.rmq_timeout');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call("rmq:consume-error-message {$this->id}");
    }
}
