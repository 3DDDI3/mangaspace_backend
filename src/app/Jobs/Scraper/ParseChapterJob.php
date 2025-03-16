<?php

namespace App\Jobs\Scraper;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Throwable;

class ParseChapterJob implements ShouldQueue
{
    use Queueable;

    public $timeout;

    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $requestDTO, private int $id)
    {
        $this->timeout = (int)config('rabbitmq.rmq_timeout') * 60;
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
