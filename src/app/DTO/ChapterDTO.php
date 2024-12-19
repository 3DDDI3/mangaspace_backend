<?php

namespace App\DTO;

class ChapterDTO
{
    public ?string $name;
    public string $url;
    public string $number;

    public function __construct(string $url, string $number, ?string $name = null)
    {
        $this->url = $url;
        $this->number = $number;
        $this->name = $name;
    }
}
