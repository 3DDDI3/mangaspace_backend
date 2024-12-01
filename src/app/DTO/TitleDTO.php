<?php

namespace App\DTO;

/**
 * TitleDTO
 */
class TitleDTO
{
    public ?string $url;
    public ?ChapterDTO $chapters;
    /**
     * TitleDTO
     *
     * @param string $url
     * @param array $chapters
     */
    public function __construct(string $url, ?ChapterDTO $chapters = [])
    {
        $this->url = $url;
        $this->chapters = $chapters;
    }
}
