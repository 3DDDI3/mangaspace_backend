<?php

namespace App\DTO;

/**
 * TitleDTO
 */
class TitleDTO
{
    public ?string $url;
    public ?array $chapters;
    /**
     * TitleDTO
     *
     * @param string $url
     * @param array $chapters
     */
    public function __construct(string $url, array $chapters = [])
    {
        $this->url = $url;
        $this->chapters = $chapters;
    }
}
