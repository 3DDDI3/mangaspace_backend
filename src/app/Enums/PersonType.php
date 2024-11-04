<?php

namespace App\Enums;

enum PersonType: int
{
    /**
     * Переводчик
     */
    case Translator = 1;
    /**
     * Автор
     */
    case Author = 2;
    /**
     * Художник
     */
    case Painter = 3;
    /**
     * Издатель
     */
    case Publisher = 4;
    /**
     * Журнал
     */
    case Magazine = 5;
}
