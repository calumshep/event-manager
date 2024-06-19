<?php

namespace App\Listeners;

use App\Mail\OrderNotification;
use App\Mail\OrderPaid;
use App\Models\Order;
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

                // Get the event
                $event = $order->tickets->first()->event;

                // Send confirmation/ticket email to the user
                Mail::to($order->orderable)->send(new OrderPaid($event, $order));

                // Send notification to event organisation's owner & team members
                $org = $event->organisation;
                Mail::to($org->user)->send(new OrderNotification($event, $order));
                foreach ($org->team as $teammember) {
                    Mail::to($teammember)->send(new OrderNotification($event, $order));
                }

                break;

            default:
                Log::warning('A webhook was received but not handled.');
        }
    }
}
