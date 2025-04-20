<?php

namespace App\Observers;

use App\Models\Title;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TitleObserver
{
    /**
     * Handle the Title "created" event.
     */
    public function created(Title $title): void
    {
        $title->fill(['rating' => $title->id])->save();
    }

    /**
     * Handle the Title "updated" event.
     */
    public function updated(Title $title): void
    {
        //
    }

    public function deleting(Title $title): void
    {
        foreach ($title->covers as $cover) {
            Storage::disk('shared')->delete($cover->path);
        }

        if (!empty($title->path))
            Storage::disk('shared')->deleteDirectory($title->path);
    }

    /**
     * Handle the Title "deleted" event.
     */
    public function deleted(Title $title): void
    {
        //
    }

    /**
     * Handle the Title "restored" event.
     */
    public function restored(Title $title): void
    {
        //
    }

    /**
     * Handle the Title "force deleted" event.
     */
    public function forceDeleted(Title $title): void
    {
        //
    }
}
