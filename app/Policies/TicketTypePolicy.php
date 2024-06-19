<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketTypePolicy
{
    public function authByEvent(User $user, Event $event): Response
    {
        return $user->can('view', $event)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can view any model.
     */
    public function viewAny(User $user): Response
    {
        /** @var Event $event */
        $event = request()->route('event');

        return $this->authByEvent($user, $event);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketType $ticket): Response
    {
        return $this->authByEvent($user, $ticket->event);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        /** @var Event $event */
        $event = request()->route('event');

        return $this->authByEvent($user, $event);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketType $ticket): Response
    {
        return $this->authByEvent($user, $ticket->event);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): Response
    {
        return $this->authByEvent($user, $event);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(): bool
    {
        return false;
    }
}
