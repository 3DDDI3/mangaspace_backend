<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.scraper.{id}.request', function () {
    // dd($user->id == $id);
    return true;
});

Broadcast::channel('admin.scraper.{id}.response', function () {
    // dd($user->id == $id);
    return true;
});

Broadcast::channel('admin.scraper.{id}.chapter-request', function () {
    // dd($user->id == $id);
    return true;
});

Broadcast::channel('admin.scraper.{id}.chapter-response', function () {
    // dd($user->id == $id);
    return true;
});

Broadcast::channel('admin.scraper.1', function () {
    return true;
});
