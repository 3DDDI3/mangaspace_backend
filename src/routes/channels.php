<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.scraper.1', function () {
    // dd($user->id == $id);
    return true;
});
