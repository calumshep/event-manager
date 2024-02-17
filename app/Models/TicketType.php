<?php

namespace App\Models;

use App\Observers\TicketTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([TicketTypeObserver::class])]
class TicketType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'time',
        'price',
        'details',
        'stripe_id',
    ];

    protected $casts = [
        'time'      => 'datetime',
        'details'   => 'array',
    ];

    /**
     * Get the event this ticket belongs to.
     *
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the orders containing this ticket type.
     *
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_ticket')
            ->using(OrderTicket::class)
            ->withTimestamps();
    }
}
