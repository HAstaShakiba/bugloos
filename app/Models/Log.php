<?php

namespace App\Models;

use App\FilterHandler\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    use Filterable;

    protected $casts = [
        'called_at' => 'datetime',
    ];
}
