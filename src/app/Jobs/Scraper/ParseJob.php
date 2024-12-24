<?php

namespace App\Jobs\Scraper;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ParseJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $requestDTO, private int $id) {}

    /** 
     * Execute the job.
     */
    public function handle(): void
    {
        $message = addslashes($this->requestDTO);
        Artisan::call("rmq:publish-parse-title-message {$this->id} {$this->job->uuid()} {$message}");
        // Artisan::call("rmq:scraper-consume-message {$this->id} {$this->job->uuid()}");
    }
}
