<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.1', function () {
    return true;
});
