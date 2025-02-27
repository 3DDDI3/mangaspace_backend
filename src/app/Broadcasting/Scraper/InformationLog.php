<?php

namespace App\Broadcasting\Scraper;

use App\Models\User;

class InformationLog
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user): array|bool
    {
        return true;
    }
}
