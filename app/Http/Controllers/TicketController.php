<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Entrant;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        return view('tickets.form', [
            'ticket'    => new Ticket(),
            'event'     => $event,
            'readonly'  => false,
            'creating'  => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Event $event, StoreTicketRequest $request)
    {
        $input = $request->validated();

        $ticket = new Ticket([
            'name'          => $input['name'],
            'description'   => $input['description'],
            'time'          => $input['time'],
            'price'         => $input['price']*100,
        ]);
        $event->tickets()->save($ticket);

        return redirect()->route('events.show', $event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Ticket $ticket)
    {
        return view('tickets.form', [
            'ticket'    => $ticket,
            'event'     => $event,
            'readonly'  => true,
            'creating'  => false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, Ticket $ticket)
    {
        return view('tickets.form', [
            'ticket'    => $ticket,
            'event'     => $event,
            'readonly'  => false,
            'creating'  => false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $input = $request->validated();

        $ticket->fill([
            'name'          => $input['name'],
            'description'   => $input['description'],
            'time'          => $input['time'],
            'price'         => $input['price']*100,
        ]);
        $ticket->save();

        return redirect()->route('events.show', $ticket->event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Ticket $ticket)
    {
        //
    }

    /**
     * Purchase the specified Ticket as the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function purchase(Request $request, Event $event)
    {
        $tickets = [];
        $entrants = [];

        foreach (array_keys($request->toArray()) as $key) {
            $parts = explode('_', $key);

            if ($parts[0] == 'ticket') {
                $i = array_search($parts[1], $tickets);
                if ($i) {
                    $entrants[$i] .= $parts[3];
                } else {
                    $tickets[$i] = $parts[1];
                    $entrants[$i] = $parts[3];
                }
            }
        }

        dd(json_encode([$tickets, $entrants]));

        return auth()->user()->checkout(null, [
            // Checkout options
            'success_url'   =>
                route('events.tickets.purchase.success', $event) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'    => route('events.tickets.purchase', $event).'?checkout=cancelled',

            // Data about the ticket order
            'metadata' => [
                'tickets_bought' => json_encode([$tickets, $entrants]),
            ]
        ]);
    }

    public function success()
    {

    }

    /**
     * Refund the specified Ticket to the original purchaser.
     *
     * @param \App\Models\Event $event
     * @param \App\Models\Ticket $ticket
     *
     * @return void
     */
    public function refund(Event $event, Ticket $ticket)
    {

    }
}
