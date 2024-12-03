<?php

namespace App\DTO;

class RequestDTO
{
    public ?string $pages;
    public ?TitleDTO $titleDTO;
    public ?ScraperDTO $scraperDTO;
    /**
     * Request object
     *
     * @param string $pages
     * @param TitleDTO $titleDTO
     */
    public function __construct(string $pages = null, ?TitleDTO $titleDTO = null, ?ScraperDTO $scraperDTO = null)
    {
        $this->scraperDTO = $scraperDTO;
        $this->pages = $pages;
        $this->titleDTO = $titleDTO;
    }
}
