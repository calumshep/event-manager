<?php

namespace App\Models;

use DateInterval;
use DatePeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start',
        'end',
        'slug',
        'short_desc',
        'long_desc',
        'organisation_id',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end'   => 'datetime',
    ];

    /**
     * Get the user who owns this event.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organisation which owns this event.
     *
     * @return BelongsTo
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the tickets relevant to this event.
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    /**
     * Returns an array of dates (inclusive of start & end for multi-day events) on which this event occurs.
     *
     * @return DatePeriod|array
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
}
