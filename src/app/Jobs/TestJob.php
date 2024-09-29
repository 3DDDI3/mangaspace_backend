<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class TestJob implements ShouldQueue
{
    use Queueable;

    private $user;

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

        Artisan::call('rmq:scraper-consume-message ' . addslashes($this->user->toJson()) . " " . $this->job->uuid());
    }
}
