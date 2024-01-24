<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Order;
use App\Models\TicketType;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Show the checkout page for the specified event.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function checkout(Event $event, Request $request)
    {
        $tickets = [];

        $quantities = $request->toArray();
        $keys = array_keys($quantities);

        // Hack-y way to get all the tickets requested on the form (ignoring 0 quantities) and assign quantity to object
        for ($i = 1; $i < count($quantities); $i++) {
            $key = $keys[$i];

            if($quantities[$key]) {
                $ticket = TicketType::find(explode('_', $key)[1]);
                $ticket->quantity = $quantities[$key];
                $tickets[$i] = $ticket;
            }
        }

        return view('tickets.checkout', [
            'event'     => $event,
            'tickets'   => $tickets,
        ]);
    }

    /**
     * Purchase the specified tickets (in the request) as the authenticated user.
     *
     * @param \App\Models\Event $event
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function purchase(Event $event, Request $request)
    {
        // Start a new order
        $order = new Order([
            'checkout_id'   => '',
            'total_amount'  => 0,
        ]);
        if (auth()->user()) {
            // If logged in, associate the authenticated user with the order
            $order->orderable()->associate(auth()->user());
        } else {
            // Otherwise, try to find a guest record for the given email address
            $guest = Guest::where('email', $request->email)->first();
            if ($guest) {
                $order->orderable()->associate($guest);
            } else {
                // If no guest record exists, create it and associate with that
                $order->orderable()->associate(new Guest(['email' => $request->email]));
            }
        }
        $order->save();

        $input = $request->toArray();

        // Iterate through all the possible ticket types for the event
        foreach($event->tickets as $ticket_type) {
            // Check if ticket type has been ordered at least once
            $key = 'quantity_'.$ticket_type->id;
            if (key_exists($key, $input)) {
                // For each ticket ordered...
                for ($j = 0; $j < $input[$key]; $j++) {
                    $metadata = [];
                    // Iterate through all required ticket details
                    foreach ($ticket_type->details as $name => $detail) {
                        $metadata[$name] = $input[$name.'_'.$ticket_type->id][$j];
                    }
                    $ticket_type->metadata = $metadata;

                    // Attach the ticket to the order with the ticket holder's name and supplied metadata
                    $order->tickets()->attach($ticket_type->id, [
                        'name'      => $input['name_'.$ticket_type->id][$j],
                        'metadata'  => $metadata,
                    ]);
                    $order->total_amount += $ticket_type->price;
                    $order->save();
                }
            }
        }

        /* Redirect to Stripe to process payment
        return auth()->user()->checkout(null, [
            // Checkout options
            'success_url' =>
                route('events.tickets.purchase.success', $order) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' =>
                route('events.tickets.purchase', $order).'?checkout=cancelled',

            // Pass order ID to Stripe
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);
        */
    }

    public function success()
    {

    }
}
