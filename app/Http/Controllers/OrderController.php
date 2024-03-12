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
    public function __construct()
    {
        // Use the App\Policies\EventPolicy class to authorize
        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Show all the user's paid orders including tickets.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('orders.index', [
            'orders' => auth()->user()->orders
                // Only show paid orders
                ->where('paid', '=', true)
                // Include tickets (necessary to get ticket quantity and related event
                ->load('tickets')
                // Paginate
                ->paginate(10),
        ]);
    }

    /**
     * Show the specified order.
     *
     * @param \App\Models\Order $order
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Order $order)
    {
        return view('orders.show', [
            'order' => $order->load('tickets'),
            'event' => $order->tickets->first()->event,
        ]);
    }

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

            if ($quantities[$key]) {
                $ticket = TicketType::find(explode('_', $key)[1]);
                $ticket->quantity = $quantities[$key];

                // Do not proceed if more tickets are requested than available
                if ($ticket->capacity && $ticket->quantity > $ticket->remaining()) {
                    return redirect()->back()
                        ->withErrors("You cannot purchase more tickets than are available.");
                }

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
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Cashier\Checkout
     * @throws \Exception
     */
    public function purchase(Event $event, Request $request)
    {
        /*
         * Process a new order
         */
        $input = $request->validate([
            'buyer_email'       => 'sometimes|required|email',
            'buyer_phone'       => 'sometimes|required',
            'special_requests'  => 'nullable',
        ]);

        $order = new Order([ // start a new order
            'checkout_id'       => '',
            'total_amount'      => 0,
            'special_requests'  => $input['special_requests'],
        ]);

        if (auth()->user()) {   // if logged in, associate the authenticated user with the order
            $order->orderable()->associate(auth()->user());
        } else {                // if not logged in, associate with existing or new Guest instance
            $order->orderable()->associate(Guest::updateOrCreate(
                ['email'         => $input['buyer_email']], // use these values to find an existing Guest record
                ['phone_number'  => $input['buyer_phone']]  // update the existing Guest or create a new one with these
            ));
        }
        $order->save();

        $checkout_items = []; // create empty array to store items to send to Stripe Checkout
        $tickets = $request->toArray();

        /*
         * Process selected tickets
         */
        foreach($event->tickets as $ticket_type) {  // iterate through all the possible ticket types for purchased ones
            $key = 'quantity_'.$ticket_type->id;
            if (key_exists($key, $tickets)) {       // check if ticket type has been ordered at least once
                $quantity = $tickets[$key];         // get the number of tickets purchased

                if ($ticket_type->capacity && $quantity > $ticket_type->remaining()) {
                    return redirect()->back()       // do not proceed if more tickets requested than available
                        ->withErrors("You cannot purchase more tickets than are available.");
                }

                /*
                 * Process any relevant ticket details
                 */
                for ($j = 0; $j < $quantity; $j++) {                      // iterate through each ticket
                    $metadata = [];
                    foreach ($ticket_type->details as $name => $detail) { // process selected ticket details as metadata
                        $metadata[$name] = $tickets[$name.'_'.$ticket_type->id][$j];
                    }
                    $ticket_type->metadata = $metadata;

                    $order->tickets()->attach($ticket_type->id, [         // attach ticket to order with name + metadata
                        'ticket_holder_name'    => $tickets['name_'.$ticket_type->id][$j],
                        'metadata'              => $metadata,
                    ]);

                    $order->total_amount += $ticket_type->price;          // update rolling order total
                    $order->save();                                       // save order to database
                }

                $checkout_items[$ticket_type->stripe_id] = $quantity;     // add ticket type with quantity to checkout
            }
        }

        /*
         * Create Stripe checkout session
         */
        $checkout_options = [
            'success_url'   =>      // redirect after successful checkout
                route('event.tickets.purchase.success', $event) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'    =>      // redirect if checkout actively cancelled by user
                route('event.tickets.cancelled', $event),
            'metadata'      => [    // give Stripe the order ID so it can pass this back in the webhook after payment
                'order_id' => $order->id],
            'expires_at' =>         // set checkout session expiry to 1 hour from now
                now()->addHour()->timestamp
        ];

        if (auth()->user()) {       // start a checkout session on the authenticated user
            return auth()->user()->checkout($checkout_items, $checkout_options);
        } else {                    // otherwise start a guest checkout and pass the email along autocompleted
            $checkout_options['customer_email'] = $input['buyer_email'];
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
