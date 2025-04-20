<?php

namespace App\Observers;

use App\Models\Chapter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChapterObserver
{
    /**
     * Handle the Chapter "created" event.
     */
    public function created(Chapter $chapter): void
    {
        $chapter->fill(['rating' => $chapter->id])->save();
    }

    /**
     * Handle the Chapter "updated" event.
     */
    public function updated(Chapter $chapter): void
    {
        //
    }

    public function deleting(Chapter $chapter): void
    {
        if (!empty($chapter->path))
            Storage::disk('shared')->deleteDirectory($chapter->path);
    }

    /**
     * Handle the Chapter "deleted" event.
     */
    public function deleted(Chapter $chapter): void
    {
        //
    }

    /**
     * Handle the Chapter "restored" event.
     */
    public function restored(Chapter $chapter): void
    {
        //
    }

    /**
     * Handle the Chapter "force deleted" event.
     */
    public function forceDeleted(Chapter $chapter): void
    {
        //
    }
}
