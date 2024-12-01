<?php

namespace App\DTO;

/**
 * TitleDTO
 */
class ChapterDTO
{
    public ?string $name;
    public ?string $url;
    public ?string $number;

    public function __construct(string $name, string $url, string $number)
    {
        $this->name = $name;
        $this->url = $url;
        $this->number = $number;
    }
}
