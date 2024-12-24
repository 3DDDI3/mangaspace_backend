<?php

namespace App\DTO;

class BaseDTO
{
    public array $pages;
    public TitleDTO $titleDTO;
    public ScraperDTO $scraperDTO;

    public function __construct(TitleDTO $titleDTO, ScraperDTO $scraperDTO, array $pages = [])
    {
        $this->titleDTO = $titleDTO;
        $this->scraperDTO = $scraperDTO;
        $this->pages = $pages;
    }
}
