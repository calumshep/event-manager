<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Billable, HasApiTokens, HasFactory, HasRoles, Notifiable /* , MustVerifyEmail */;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the events belonging to the user.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the organisations belonging to the user.
     */
    public function organisations(): HasMany
    {
        return $this->hasMany(Organisation::class);
    }

    /**
     * Get the orders belonging to the user.
     */
    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    /**
     * Get the organisations this user is a member of.
     */
    public function orgMemberships(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Organisation::class,
            table: 'organisations_users',
            foreignPivotKey: 'user_id',
            relatedPivotKey: 'organisation_id'
        );
    }

    /**
     * Returns the events that this user owns, as well as events belonging to organisations which this user is a
     * member of.
     */
    public function getManagableEvents(): Collection
    {
        return Event::whereIn('organisation_id', $this->orgMemberships->pluck('id'))->get()->merge($this->events);
    }

    /**
     * Returns true if the user is in the event organisation's team.
     */
    public function canManageEvent(Event $event): bool
    {
        return $event->organisation->team->contains($this) || $event->user == $this;
    }
}
