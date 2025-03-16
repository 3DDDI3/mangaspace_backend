<?php

namespace App\Jobs\Scraper;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ParseTitleJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        Artisan::call("rmq:publish-parse-title-message {$this->id} {$this->job->uuid()} {$message}");
        Artisan::call("rmq:consume-parse-title-message {$this->id} {$this->job->uuid()}");
    }
}
