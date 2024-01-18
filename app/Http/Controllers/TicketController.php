<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Event;
use App\Models\Ticket;

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
//        dd($event->days());
        return view('tickets.form', [
            'ticket'        => new Ticket(),
            'event'         => $event,
            'event_days'    => $event->days(),
            'readonly'      => false,
            'creating'      => true,
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
            'description'   => clean($request->description),
            'time'          => $request->time,
            'price'         => $input['price']*100,
        ]);
        $event->tickets()->save($ticket);

        return redirect()->route('events.tickets.show', [$event, $ticket]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Ticket $ticket)
    {
        return view('tickets.form', [
            'ticket'        => $ticket,
            'event'         => $event,
            'event_days'    => $event->days(),
            'readonly'      => true,
            'creating'      => false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, Ticket $ticket)
    {
        return view('tickets.form', [
            'ticket'        => $ticket,
            'event'         => $event,
            'event_days'    => $event->days(),
            'readonly'      => false,
            'creating'      => false,
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
            'description'   => clean($request->description),
            'time'          => $request->time,
            'price'         => $input['price']*100,
        ]);
        $ticket->save();

        return redirect()->route('events.tickets.show', [$event, $ticket])->with([
            'status' => 'Ticket details updated successfully.'
        ]);
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
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function purchase(Event $event)
    {
        return auth()->user()->checkout(null, [
            // Checkout options
            'success_url'   =>
                route('events.tickets.purchase.success', $event) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'    => route('events.tickets.purchase', $event).'?checkout=cancelled',

            // Data about the ticket order
            'metadata' => [

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
