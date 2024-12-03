<?php

namespace App\DTO;

class ScraperDTO
{
    public ?string $action;
    public ?string $engine;

    public function __construct(?string $action = null, ?string $engine = null)
    {
        $this->action = $action;
        $this->engine = $engine;
    }
}
