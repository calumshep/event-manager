<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Order;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Checkout;
use Stripe\Exception\ApiErrorException;

class OrderController extends Controller
{
    /**
     * Show the checkout page for the specified event.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function checkout(Event $event, Request $request)
    {
        $tickets = [];

        $quantities = $request->toArray();
        $keys = array_keys($quantities);
        $total = 0;

        // Hack-y way to get all the tickets requested on the form (ignoring 0 quantities) and assign quantity to object
        for ($i = 1; $i < count($quantities); $i++) {
            $key = $keys[$i];

            if($quantities[$key]) {
                $ticket = TicketType::find(explode('_', $key)[1]);
                $ticket->quantity = $quantities[$key];
                $total += $ticket->price * $ticket->quantity;
                $tickets[$i] = $ticket;
            }
        }

        if (sizeof($tickets) == 0) {
            return redirect()->back()->withErrors("You must select at least 1 ticket.");
        }

        return view('tickets.checkout', [
            'event'     => $event,
            'tickets'   => $tickets,
            'total'     => $total,
        ]);
    }

    /**
     * Purchase the specified tickets (in the request) as the authenticated user or as a guest.
     *
     * Request should look like:
     *      "quantity_1" => "2"         // Number supplied is TicketType ID.
 *          "name_1" => array:2 [▼      // ". Number of items in name_ array should match quantity above.
     *          0 => "Test 1"
     *          1 => "test2"
     *      ]
     *      "quantity_2" => "1"         // Second TicketType ordered.
     *      "name_2" => array:1 [▼
     *          0 => "test 3"
     *      ]
     *
     * @param \App\Models\Event $event
     * @param \Illuminate\Http\Request $request
     *
     * @return Checkout
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
                $order->orderable()->associate(Guest::create(['email' => $request->buyer_email]));
            }
        }
        $order->save();

        $input = $request->toArray();

        // Create empty array to store items to send to Stripe Checkout
        $checkout_items = [];

        // Iterate through all the possible ticket types for the event to find purchased ones
        foreach($event->tickets as $ticket_type) {

            // Check if ticket type has been ordered at least once
            $key = 'quantity_'.$ticket_type->id;
            if (key_exists($key, $input)) {

                // Get the number of tickets purchased
                $quantity = $input[$key];

                // For each ticket ordered...
                for ($j = 0; $j < $quantity; $j++) {

                    // Iterate through all required ticket details and set metadata on ticket instance
                    $metadata = [];
                    foreach ($ticket_type->details as $name => $detail) {
                        $metadata[$name] = $input[$name.'_'.$ticket_type->id][$j];
                    }
                    $ticket_type->metadata = $metadata;

                    // Attach the ticket to the order with the ticket holder's name and any supplied metadata
                    $order->tickets()->attach($ticket_type->id, [
                        'ticket_holder_name'    => $input['name_'.$ticket_type->id][$j],
                        'metadata'              => $metadata,
                    ]);

                    // Update order total and save
                    $order->total_amount += $ticket_type->price;
                    $order->save();
                }

                // Add the total quantity of this ticket to the checkout items for Stripe
                $checkout_items[$ticket_type->stripe_id] = $quantity;
            }
        }

        // Checkout options
        $checkout_options = [
            'success_url' =>
                route('event.tickets.purchase.success', $event) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' =>
                route('event.tickets.cancelled', $event),

            // Pass order ID to Stripe
            'metadata' => [
                'order_id' => $order->id,
            ],
        ];

        // Redirect to Stripe to process payment
        if (auth()->user()) {
            return auth()->user()->checkout($checkout_items, $checkout_options);
        } else {
            return Checkout::guest()->create($checkout_items, $checkout_options);
        }
    }

    /**
     * Handle Stripe redirecting back form a cancelled Checkout session.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelled(Event $event)
    {
        return redirect()->route('home.event', $event)->with([
            'warning' => "Your ticket purchase was cancelled.",
        ]);
    }

    /**
     * Handle successful completion of a Stripe Checkout session.
     *
     * @param \App\Models\Event $event
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function success(Event $event, Request $request)
    {
        try {
            return view('tickets.success', [
                'event'     => $event,
                'checkout'  => Cashier::stripe()->checkout->sessions->retrieve($request->get('session_id'))
            ]);
        } catch (ApiErrorException $e) {
            Log::error($e);

            return redirect()->route('home.event', $event)->withErrors([
                "An unknown error occurred."
            ]);
        }
    }
}
