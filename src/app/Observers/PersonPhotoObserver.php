<?php

namespace App\Observers;

use App\Models\PersonPhoto;

class PersonPhotoObserver
{
    /**
     * Handle the PersonPhoto "created" event.
     */
    public function created(PersonPhoto $personPhoto): void
    {
        $personPhoto->fill(['rating' => $personPhoto->id])->save();
    }

    /**
     * Handle the PersonPhoto "updated" event.
     */
    public function updated(PersonPhoto $personPhoto): void
    {
        //
    }

    /**
     * Handle the PersonPhoto "deleted" event.
     */
    public function deleted(PersonPhoto $personPhoto): void
    {
        //
    }

    /**
     * Handle the PersonPhoto "restored" event.
     */
    public function restored(PersonPhoto $personPhoto): void
    {
        //
    }

    /**
     * Handle the PersonPhoto "force deleted" event.
     */
    public function forceDeleted(PersonPhoto $personPhoto): void
    {
        //
    }
}
