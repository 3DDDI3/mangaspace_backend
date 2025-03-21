<?php

namespace App\Filters;

use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class ProductFilter
 */
class TitleChapterFilter extends Filter
{
    protected function orderByAsc(string $type): Builder
    {
        foreach (explode(",", $type) as $order) {
            $this->builder->orderBy($order);
        }

        return $this->builder;
    }
}
