<?php

namespace App\DTO;

class LogDTO
{
    public ?string $message = null;
    public bool $isLast = false;

    public function __construct(?string $message = null, bool $isLast = false)
    {
        $this->message = $message;
        $this->isLast = $isLast;
    }
}
