<?php

namespace App\Observers;

use App\Http\Support\StripeHelper;
use App\Models\TicketType;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class TicketTypeObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function creating(TicketType $ticket_type): void
    {
        // Create product in Stripe
        try {
            $ticket_type->stripe_id = StripeHelper::createPrice($ticket_type)->id;
        } catch (ApiErrorException $e) {
            Log::error($e);
        }
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updating(TicketType $ticket_type): void
    {
        // Update product in Stripe
        try {
            $ticket_type->stripe_id = StripeHelper::updatePrice($ticket_type)->id;
        } catch (ApiErrorException $e) {
            Log::error($e);
        }
    }

    /**
     * Handle the Event "deleting" event.
     */
    public function deleting(TicketType $ticket_type): void
    {
        try {
            StripeHelper::deletePrice($ticket_type);
        } catch (ApiErrorException $e) {
            Log::error($e);
        }
    }
}
