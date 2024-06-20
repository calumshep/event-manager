<?php

namespace App\Observers;

use App\Models\Event;
use App\Support\StripeHelper;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class EventObserver
{
    /**
     * Handle the Event "creating" event.
     */
    public function creating(Event $event): void
    {
        // Create product in Stripe
        try {
            $product = StripeHelper::createProduct($event);
            $event->stripe_id = $product->id;
        } catch (ApiErrorException $e) {
            Log::error($e);
        }
    }

    /**
     * Handle the Event "updating" event.
     */
    public function updating(Event $event): void
    {
        // Update product in Stripe
        try {
            $event->stripe_id = StripeHelper::updateProduct($event)->id;
        } catch (ApiErrorException $e) {
            Log::error($e);
        }
    }

    /**
     * Handle the Event "deleting" event.
     */
    public function deleting(Event $event): void
    {
        try {
            StripeHelper::deleteProduct($event);
        } catch (ApiErrorException $e) {
            Log::error($e);
        }
    }
}
