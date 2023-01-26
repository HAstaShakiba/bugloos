<?php

namespace App\FilterHandler;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilters
{
    /**
     * The request object.
     */
    protected Request $request;

    /**
     * The builder instance.
     */
    protected Builder $builder;

    /**
     * Create a new QueryFilters instance.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters to the builder.
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name)) {
                continue;
            }

            if (is_array($value) && count($value) || strlen($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }

        return $this->builder;
    }

    /**
     * Get all request filters data.
     */
    public function filters(): array
    {
        return $this->request->all();
    }
}
