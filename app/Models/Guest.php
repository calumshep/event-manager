<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Cashier\Billable;

class Guest extends Model
{
    use Billable;

    protected $fillable = [
        'email',
        'phone_number',
    ];

    /**
     * Get the orders belonging to the guest.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'orderable');
    }
}
