<?php

namespace App\DTO;

class TitleDTO
{
    public ?string $url;
    public ?string $name;
    public array $chapterDTO;
    public function __construct(?string $url = null, ?string $name = null, array $chapterDTO = [])
    {
        $this->url = $url;
        $this->name = $name;
        $this->chapterDTO = $chapterDTO;
    }
}
