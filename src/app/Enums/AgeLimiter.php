<?php

namespace App\Enums;

enum AgeLimiter: int
{
    /**
     * Все
     */
    case all = 1;
    /**
     * 16+
     */
    case minor = 2;
    /**
     * 18+
     */
    case adult = 3;
}
