<?php

namespace App\Filters;

use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class ProductFilter
 */
class TitleFilter extends Filter
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

    protected function slug(string $slug)
    {
        return $this->builder->where(['slug' => $slug]);
    }

    protected function ruName(string $runame)
    {
        return $this->builder->where(['ru_name' => $runame]);
    }

    protected function engName(string $eng_name)
    {
        return $this->builder->where(['eng_name' => $eng_name]);
    }

    protected function search(string $search): Builder
    {
        return $this->builder->where('ru_name', 'like', "%{$search}")
            ->orWhere('created_at', 'like', "%{$search}%");
        // ->orWhere('created_at', 'like', "%{$search}%")
        // ->orWhere('updated_at', 'like', "%{$search}%")
        // ->orWhere('name', 'like', "%{$search}%");
    }
}
