<?php

namespace App\Jobs\Scraper;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class ParseChapterJob implements ShouldQueue
{
    use Queueable;

    public $timeout;

    public $tries = 1;

    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $requestDTO, private int $id)
    {
        $this->timeout = config('app.rmq_timeout');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $message = addslashes($this->requestDTO);

        Artisan::call("rmq:publish-parse-chapter-message {$this->id} {$this->job->uuid()} {$message}");
        Artisan::call("rmq:consume-parse-chapter-message {$this->id} {$this->job->uuid()}");
    }
}
