<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderTicket extends Pivot
{
    protected $fillable = [
        'ticket_holder_name',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
