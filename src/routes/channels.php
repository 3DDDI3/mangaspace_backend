<?php

use App\Broadcasting\Scraper\GetChapter;
use App\Broadcasting\Scraper\ParseChapter;
use App\Broadcasting\Scraper\ParseTitle;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.{id}.scraper.parseChapter', ParseChapter::class);

Broadcast::channel('admin.{id}.scraper.getChapter', GetChapter::class);

Broadcast::channel('admin.{id}.scraper.parseTitle', ParseTitle::class);
