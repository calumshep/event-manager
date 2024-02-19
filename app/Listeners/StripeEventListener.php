<?php

namespace App\Listeners;

use App\Mail\OrderPaid;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;
use Log;
use Stripe\Event;

class StripeEventListener
{
    /**
     * Handle the event.
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @throws \Exception
     */
    public function handle(WebhookReceived $event): void
    {
        switch ($event->payload['type']) {

            // Checkout session completed, i.e. paid.
            case 'checkout.session.completed':

                /**
                 * Constructs a StripeObject from the webhook payload for OOP access
                 *
                 * @var \Stripe\StripeObject|null $stripeObject
                 */
                $stripeObject = Event::constructFrom($event->payload)->data?->object;

                // Gets the 'line items' (products) that were bought in the Checkout Session
                $session = Cashier::stripe()->checkout->sessions->retrieve($stripeObject->id);

                // Find the Order and mark as paid
                $order = Order::findOrFail($session->metadata['order_id']);
                $order->paid = true;
                $order->checkout_id = $stripeObject->id;
                $order->save();

                // Send confirmation/ticket email to the user
                Mail::to($order->orderable)->send(new OrderPaid($order->tickets->first()->event, $order));

                break;

            default:
                Log::warning("A webhook was received but not handled.");
        }
    }
}
