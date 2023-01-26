<?php

namespace App\FilterHandler;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Filter a result set.
     */
    public function scopeFilter(Builder $query, QueryFilters $filters): Builder
    {
        return $filters->apply($query);
    }
}
