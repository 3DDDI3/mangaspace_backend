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
     * @return string
     */
    public function parseString(string $string): string
    {
        $substr = explode(",", $string);
        $pages = null;

        for ($i = 0; $i < count($substr); $i++) {
            preg_match("/(\d+)\.{2}(\d+)/", $substr[$i], $matches);

            for ($j = $matches[1]; $j <= $matches[2]; $j++) {
                if ($j == $matches[2])
                    $pages .= $j;
                else
                    $pages .= $j . ",";
            }

            if ($i != count($substr) - 1)
                $pages .= ",";
        }

        return $pages;
    }
}
