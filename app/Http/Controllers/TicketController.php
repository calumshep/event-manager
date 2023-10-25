<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
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
            'event'     => $ticket->event,
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
            'event'     => $ticket->event,
            'readonly'  => false,
            'creating'  => false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Event $event, Ticket $ticket)
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
     * Show the view to check out a ticket purchase.
     *
     * @param Request $request
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function checkout(Request $request, Event $event)
    {
        $input = $request->toArray();

        foreach ($event->tickets as $ticket) {
            $ticket->quantity = (int) $input['quantity_'.$ticket->id];
        }
        $tickets = $event->tickets->reject(function (Ticket $ticket) { return $ticket->quantity <= 0; });

        return view('tickets.checkout', [
            'event'     => $event,
            'tickets'   => $tickets,
        ]);
    }

    /**
     * Purchase the specified Ticket as the authenticated user.
     *
     * @param \App\Models\Event $event
     * @param \App\Models\Ticket $ticket
     *
     * @return void
     */
    public function purchase(Event $event, Ticket $ticket)
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
