<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderTicket extends Pivot
{
    protected $fillable = [
        'name',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
