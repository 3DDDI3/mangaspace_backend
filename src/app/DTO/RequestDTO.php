<?php

namespace App\DTO;

class RequestDTO
{
    /**
     * Request object
     *
     * @param string $pages
     * @param TitleDTO $titleDTO
     */
    public function __construct(public string $pages, public TitleDTO $titleDTO) {}
}
