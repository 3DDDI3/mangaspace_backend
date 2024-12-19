<?php

namespace App\DTO;

class ScraperDTO
{
    public string $action;
    public string $engine;
    public function __construct(string $action, string $engine)
    {
        $this->action = $action;
        $this->engine = $engine;
    }
}
