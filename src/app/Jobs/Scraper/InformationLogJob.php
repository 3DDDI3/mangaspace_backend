<?php

namespace App\Jobs\Scraper;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class InformationLogJob implements ShouldQueue
{
    use  Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $id)
    {
        $this->timeout = (int)config('rabbitmq.rmq_timeout') * 60;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call("rmq:consume-log-message {$this->id}");
    }
}
