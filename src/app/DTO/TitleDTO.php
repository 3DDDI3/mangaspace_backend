<?php

namespace App\DTO;

/**
 * TitleDTO
 */
class TitleDTO
{
    /**
     * TitleDTO
     *
     * @param string $url
     * @param string $chapters
     */
    public function __construct(public string $url, public string $chapters) {}
}
