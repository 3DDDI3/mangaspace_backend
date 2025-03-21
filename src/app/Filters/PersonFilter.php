<?php

namespace App\Filters;

use App\Enums\PersonType;
use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProductFilter
 */
class PersonFilter extends Filter
{
    protected function type(string $type): Builder
    {
        return $this->builder->where('person_type_id', $type);
    }
} 
