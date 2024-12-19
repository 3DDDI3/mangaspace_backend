<?php

namespace App\DTO;

class BaseDTO
{
    public ?string $page;
    public TitleDTO $titleDTO;
    public ScraperDTO $scraperDTO;

    public function __construct(TitleDTO $titleDTO, ScraperDTO $scraperDTO, ?string $page = null)
    {
        $this->titleDTO = $titleDTO;
        $this->scraperDTO = $scraperDTO;
        $this->page = $page;
    }
}
