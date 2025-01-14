<?php

namespace App\Services;

/**
 * Сервис для работы с строкой из Request
 */
class RequestStringService
{
    /**
     * Парсинг строки формата 1..5,10 в формат 1,2,3,4,5,10
     *
     * @param string $string
     * @return array
     */
    public function parseString(string $string): array
    {
        $substr = explode(",", $string);
        $pages = [];

        for ($i = 0; $i < count($substr); $i++) {
            if (preg_match("/(\d+)\.{2}(\d+)/", $substr[$i], $matches)) {
                for ($j = (int)$matches[1]; $j <= (int)$matches[2]; $j++) {
                    if ($j == $matches[2])
                        $pages[] = $j;
                    else
                        $pages[] = $j;
                }
            } else {
                $pages[] = (int)$substr[$i];
            }
        }

        natsort($pages);

        $pages = array_values(array_unique($pages));

        return $pages;
    }
}
