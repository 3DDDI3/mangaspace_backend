<?php

namespace App\Observers;

use App\Models\TitleCover;
use Illuminate\Support\Facades\Storage;

class TitleCoverObserver
{
    /**
     * Handle the TitleCover "created" event.
     */
    public function created(TitleCover $titleCover): void
    {
        //
    }

    /**
     * Handle the TitleCover "updated" event.
     */
    public function updated(TitleCover $titleCover): void
    {
        //
    }

    /**
     * Удаление обложки тайтла при удалении его из базы
     *
     * @param TitleCover $cover
     * @return void
     */
    public function deleting(TitleCover $cover): void
    {
        if (!empty($cover->path)) {
            Storage::delete("media/titles/{$cover->title->path}/covers/{$cover->path}");
        }
    }

    /**
     * Handle the TitleCover "deleted" event.
     */
    public function deleted(TitleCover $titleCover): void
    {
        //
    }

    /**
     * Handle the TitleCover "restored" event.
     */
    public function restored(TitleCover $titleCover): void
    {
        //
    }

    /**
     * Handle the TitleCover "force deleted" event.
     */
    public function forceDeleted(TitleCover $titleCover): void
    {
        //
    }
}
