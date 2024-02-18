<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'checkout_id',
        'total_amount',
        'paid',
        'orderable_id',
        'orderable_type',
    ];

    /**
     * Get the User or Guest object related to this order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the tickets associated with this order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(TicketType::class, 'order_ticket')
            ->withTimestamps()
            ->withPivot('ticket_holder_name', 'metadata')
            ->using(OrderTicket::class)
            ->as('data');
    }
}
