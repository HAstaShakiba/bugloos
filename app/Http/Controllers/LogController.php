<?php

namespace App\Http\Controllers;

use App\FilterHandler\Filters\LogFilters;
use App\Models\Log;

class LogController extends Controller
{
    public function __invoke(LogFilters $filters)
    {
        $count = Log::filter($filters)->count();

        return response()->json(compact('count'));
    }
}
