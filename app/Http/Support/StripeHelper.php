<?php

namespace App\Http\Support;

use App\Models\Event;
use App\Models\TicketType;
use Laravel\Cashier\Cashier;
use Stripe\Price;
use Stripe\Product;

class StripeHelper
{
    /**
     * Compile details for the given event.
     *
     * @param \App\Models\Event $event
     *
     * @return array
     */
    public static function eventDetails(Event $event): array
    {
        return [
            'name'          => $event->name,
            'description'   => strip_tags($event->short_desc),
        ];
    }

    /**
     * Create a new Stripe product for the given event.
     *
     * @param \App\Models\Event $event
     *
     * @return Product
     * @throws \Stripe\Exception\ApiErrorException
     */
    public static function createNewProduct(Event $event): Product
    {
        return Cashier::stripe()->products->create(self::eventDetails($event));
    }

    /**
     * Update an existing Stripe product for an existing event.
     *
     * @param \App\Models\Event $event
     *
     * @return Product
     * @throws \Stripe\Exception\ApiErrorException
     */
    public static function updateProduct(Event $event): Product
    {
        return Cashier::stripe()->products->update($event->stripe_id, self::eventDetails($event));
    }

    /**
     * Delete an existing Stripe product for an existing event.
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public static function deleteProduct(Event $event): void
    {
        Cashier::stripe()->products->delete($event->stripe_id);
    }

    /**
     * Create a new Stripe price for the given ticket type, in the product for the associated event.
     *
     * @param \App\Models\TicketType $ticket
     *
     * @return Price
     * @throws \Stripe\Exception\ApiErrorException
     */
    public static function createNewPrice(TicketType $ticket): Price
    {
        return Cashier::stripe()->prices->create([
            'currency'      => config('cashier.currency'),
            'unit_amount'   => $ticket->price,
            'nickname'      => $ticket->name,
            'product'       => $ticket->event->stripe_id,
        ]);
    }

    /**
     * Update the relevant Stripe price for the given ticket type, in the product for the associated event.
     *
     * @param \App\Models\TicketType $ticket
     *
     * @return Price
     * @throws \Stripe\Exception\ApiErrorException
     */
    public static function updatePrice(TicketType $ticket): Price
    {
        return Cashier::stripe()->prices->update($ticket->stripe_id, [
            'currency_options' => [
                config('cashier.currency') => $ticket->price,
            ],
            'nickname'  => $ticket->name,
        ]);
    }

    /**
     * Delete the relevant Stripe price for the given ticket type, in the product for the associated event.
     * NOTE: Stripe API does not support deleting, so this method instead marks the price as:
     *  'active' => false
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public static function deletePrice(TicketType $ticket): void
    {
        Cashier::stripe()->prices->update($ticket->stripe_id, [
            'active' => false,
        ]);
    }
}
