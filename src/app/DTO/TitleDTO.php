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
     * @param array $chapters
     */
    public function __construct(public string $url, public array $chapters) {}
}
