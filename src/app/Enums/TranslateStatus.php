<?php

enum TranslateStatus: int
{
    /**
     * Продолжается
     */
    case continues = 1;
    /**
     * Завершен
     */
    case finished = 2;
    /**
     * Заморожен
     */
    case freezed = 3;
    /**
     * Прекращен
     */
    case terminated = 4;
    /**
     * Лицензировано
     */
    case licensed = 5;
    /**
     * Нет переводчика
     */
    case noTranslator = 6;
}
