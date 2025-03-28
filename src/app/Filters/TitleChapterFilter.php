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
    protected function orderByAsc(string $order): Builder
    {
        foreach (explode(",", $order) as $order) {
            $this->builder->orderBy($order);
        }

        return $this->builder;
    }

    protected function orderByDesc(string $order): Builder
    {
        foreach (explode(",", $order) as $order) {
            $this->builder->orderBy($order, 'desc');
        }

        return $this->builder;
    }

    protected function search(string $search): Builder
    {
        return $this->builder->where('volume', 'like', "%{$search}")
            ->orWhere('number', 'like', "%{$search}%")
            ->orWhere('created_at', 'like', "%{$search}%")
            ->orWhere('updated_at', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%");
    }
}
