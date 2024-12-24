<?php

namespace App\DTO;

class ChapterDTO
{
    public string $url;
    public ?string $name;
    public ?string $number;
    public bool $isLast;

    public function __construct(string $url, ?string $number = null, ?string $name = null, bool $isLast = false)
    {
        $this->url = $url;
        $this->number = $number;
        $this->name = $name;
        $this->isLast = $isLast;
    }
}
