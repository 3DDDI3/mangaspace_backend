<?php

use App\Broadcasting\Scraper\ErrorLog;
use App\Broadcasting\Scraper\GetChapter;
use App\Broadcasting\Scraper\InformationLog;
use App\Broadcasting\Scraper\ParseChapter;
use App\Broadcasting\Scraper\ParseTitle;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.{id}.scraper.parseChapters', ParseChapter::class);

Broadcast::channel('admin.{id}.scraper.getChapters', GetChapter::class);

Broadcast::channel('admin.{id}.scraper.parseTitles', ParseTitle::class);

Broadcast::channel('admin.{id}.scraper.logInformation', InformationLog::class);

Broadcast::channel('admin.{id}.scraper.logError', ErrorLog::class);
