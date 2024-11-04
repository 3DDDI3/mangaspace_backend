<?php

namespace App\Enums;

enum TitleStatus: int
{
    /**
     * Онгоинг
     */
    case continues = 1;
    /**
     * Анонс
     */
    case announcement = 2;
    /**
     * Завершен
     */
    case finished = 3;
    /**
     * Приостановлен
     */
    case suspended = 4;
    /**
     * Прекращен
     */
    case terminated = 5;
    /**
     * Лицензировано
     */
    case licensed = 6;
    /**
     * Нет переводчика
     */
    case noTranslator = 7;
}
