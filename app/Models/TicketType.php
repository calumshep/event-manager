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
        'capacity',
        'show_remaining',
        'details',
        'stripe_id',
    ];

    protected $casts = [
        'time' => 'datetime',
        'details' => 'array',
        'show_remaining' => 'bool',
    ];

    /**
     * Get the event this ticket belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the orders containing this ticket type.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_ticket')
            ->using(OrderTicket::class)
            ->withTimestamps();
    }

    /**
     * Get the number of this ticket types purchased.
     */
    public function purchased(): int
    {
        return OrderTicket::where('ticket_type_id', $this->id)->count();
    }

    /**
     * Get the number of this ticket type remaining.
     */
    public function remaining(): int
    {
        return $this->capacity - $this->purchased();
    }
}
