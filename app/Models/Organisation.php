<?php

namespace App\Models;

use App\Observers\OrganisationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([OrganisationObserver::class])]
class Organisation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'website',
        'stripe_id',
    ];

    /**
     * Get the user that owns this organisation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the events belonging to this organisation.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the users in the team for this organisation.
     */
    public function team(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'organisations_users',
            foreignPivotKey: 'organisation_id',
            relatedPivotKey: 'user_id'
        );
    }
}
