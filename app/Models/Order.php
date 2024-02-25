<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyzegs\Prunable\Concerns\SafePrunable;

class Order extends Model
{
    use HasFactory, Prunable, SoftDeletes;

    protected $fillable = [
        'checkout_id',
        'total_amount',
        'paid',
        'orderable_id',
        'orderable_type',
    ];

    protected $casts = [
        'paid' => 'bool'
    ];

    /**
     * Define that unpaid orders created more than 2 weeks ago should be pruned.
     */
    public function prunable(): Builder
    {
        return static::where('paid', false)->where('created_at', '<', now()->addWeeks(2));
    }

    /**
     * When pruning unpaid orders, delete the tickets associated with the order as well.
     */
    protected function pruning(): void
    {
        $this->tickets()->detach();
    }

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
