<?php

namespace App\DTO;

class RequestDTO
{
    public ?string $pages;
    public ?TitleDTO $titleDTO;
    /**
     * Request object
     *
     * @param string $pages
     * @param TitleDTO $titleDTO
     */
    public function __construct(string $pages = null, ?TitleDTO $titleDTO = null)
    {
        $this->pages = $pages;
        $this->titleDTO = $titleDTO;
    }
}
