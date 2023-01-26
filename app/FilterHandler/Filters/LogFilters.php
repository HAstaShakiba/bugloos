<?php

namespace App\FilterHandler\Filters;

use App\FilterHandler\QueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class LogFilters extends QueryFilters
{
    /**
     * Filter by service names.
     */
    public function serviceNames(array $serviceNames = []): Builder
    {
        return $this->builder->whereIn('service', $serviceNames);
    }

    /**
     * Filter by status.
     */
    public function status(int $status): Builder
    {
        return $this->builder->where('status', $status);
    }

    /**
     * Filter by start date.
     */
    public function startDate(string $startDate): Builder
    {
        $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $startDate);

        return $this->builder->where('called_at', '>=', $dateTime);
    }

    /**
     * Filter by end date.
     */
    public function endDate(string $endDate): Builder
    {
        $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $endDate);

        return $this->builder->where('called_at', '<=', $dateTime);
    }
}
