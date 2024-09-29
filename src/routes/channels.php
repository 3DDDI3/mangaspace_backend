<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{user}', function ($user, $id) {
    // dd($user->id == $id);
    return $user->id == $id;
});
