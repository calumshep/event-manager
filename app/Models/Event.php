<?php

namespace App\Models;

use DateInterval;
use DatePeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organisation which owns this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the tickets relevant to this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Returns an array of dates (inclusive of start & end for multi-day events) on which this event occurs.
     *
     * @return DatePeriod|array
     */
    public function days()
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
