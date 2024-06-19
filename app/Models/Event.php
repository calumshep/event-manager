<?php

namespace App\Models;

use App\Observers\EventObserver;
use DateInterval;
use DatePeriod;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

#[ObservedBy([EventObserver::class])]
class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start',
        'end',
        'slug',
        'short_desc',
        'long_desc',
        'organisation_id',
        'stripe_id',
        'type',
        'special_requests',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end'   => 'datetime',
    ];

    /**
     * Get the user who owns this event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organisation which owns this event.
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the tickets relevant to this event.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    /**
     * Returns an array of dates (inclusive of start & end for multi-day events) on which this event occurs.
     */
    public function days(): DatePeriod|array
    {
        if ($this->end) {
            return new DatePeriod(
                $this->start,
                new DateInterval('P1D'),
                $this->end,
                DatePeriod::INCLUDE_END_DATE
            );
        } else {
            return [$this->start];
        }
    }

    /**
     * Get the paid up orders which contain tickets for this event.
     */
    public function getOrders(): Collection
    {
        return $this
            ->getTickets()
            ->groupBy('order_id')
            ->map(function (Collection $order) {
                $collated_order = $order->first()->order;
                $collated_order->tickets = $order;

                return $collated_order;
            });
    }

    /**
     * Get all the ordered (and paid up) ticket instances for this event.
     */
    public function getTickets(): Collection
    {
        return OrderTicket
            ::whereIn('ticket_type_id', $this->tickets->pluck('id'))
            ->whereRelation('order', 'paid', true)
            ->get()
            ->sortByDesc('updated_at');
    }

    /**
     * Returns true if the event is of type ski race.
     */
    public function isRace(): bool
    {
        return $this->type == 'race';
    }
}
