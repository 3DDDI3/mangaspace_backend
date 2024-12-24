<?php

namespace App\DTO;

class TitleDTO
{
    public ?string $url;
    public array $chapterDTO;
    public function __construct(?string $url = null, array $chapterDTO = [])
    {
        $this->url = $url;
        $this->chapterDTO = $chapterDTO;
    }
}
