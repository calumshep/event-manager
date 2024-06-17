<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Event
 *
 * @property-read \App\Models\Organisation|null $organisation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketType> $tickets
 * @property-read int|null $tickets_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event withoutTrashed()
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Guest
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Cashier\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Guest hasExpiredGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest onGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest query()
 */
	class Guest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HelpArticle
 *
 * @property-read \App\Models\HelpCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|HelpArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpArticle query()
 */
	class HelpArticle extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HelpCategory
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HelpArticle> $articles
 * @property-read int|null $articles_count
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpCategory query()
 */
	class HelpCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $orderable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketType> $tickets
 * @property-read int|null $tickets_count
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order withoutTrashed()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderTicket
 *
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\TicketType|null $ticketType
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTicket query()
 */
	class OrderTicket extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Organisation
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $team
 * @property-read int|null $team_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\OrganisationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Organisation withoutTrashed()
 */
	class Organisation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TicketType
 *
 * @property-read \App\Models\Event|null $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\TicketTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TicketType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketType withoutTrashed()
 */
	class TicketType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation> $orgMemberships
 * @property-read int|null $org_memberships_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation> $organisations
 * @property-read int|null $organisations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Cashier\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User hasExpiredGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

