<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Support\StripeHelper;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Foundation\Http\FormRequest;
use Stripe\Exception\ApiErrorException;

class TicketTypeController extends Controller
{
    public function __construct()
    {
        // Use the App\Policies\TicketTypePolicy class to authorize
        $this->authorizeResource(TicketType::class, 'ticket_type');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        return view('tickets.form', [
            'ticket'        => new TicketType(),
            'details'       => [],
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

        $ticket_type = $event->tickets()->create([
            'name'              => $input['name'],
            'description'       => clean($request->description),
            'time'              => $request->time,
            'price'             => $input['price']*100,
            'show_remaining'    => (bool) $request->show_remaining,
            'capacity'          => $input['capacity'],
            'details'           => $this->computeDetails($request),
        ]);

        return redirect()->route('events.tickets.show', [$event, $ticket_type])->with([
            'status' => 'Ticket successfully created.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, TicketType $ticket_type)
    {
        return view('tickets.form', [
            'ticket'        => $ticket_type,
            'details'       => $ticket_type->details,
            'event'         => $event,
            'event_days'    => $event->days(),
            'readonly'      => true,
            'creating'      => false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, TicketType $ticket_type)
    {
        return view('tickets.form', [
            'ticket'        => $ticket_type,
            'details'       => $ticket_type->details,
            'event'         => $event,
            'event_days'    => $event->days(),
            'readonly'      => false,
            'creating'      => false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Event $event, TicketType $ticket_type)
    {
        $input = $request->validated();

        $ticket_type->fill([
            'name'              => $input['name'],
            'description'       => clean($request->description),
            'time'              => $request->time,
            'price'             => $input['price']*100,
            'show_remaining'    => (bool) $request->show_remaining,
            'capacity'          => $input['capacity'],
            'details'           => $this->computeDetails($request),
        ]);
        $ticket_type->save();

        return redirect()->route('events.tickets.show', [$event, $ticket_type])->with([
            'status' => 'Ticket details updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, TicketType $ticket_type)
    {
        if ($ticket_type->orders->count()) {
            return redirect()->back()->withErrors([
                "You cannot delete a ticket which has orders."
            ]);
        }

        $ticket_type->delete();

        return redirect()->route('events.show', $event)->with([
            'warning' => "Ticket successfully deleted.",
        ]);
    }

    /**
     * Compute details for information collection on ticket.
     *
     * @return array
     */
    public function computeDetails(FormRequest $request)
    {
        $details = [];
        if ($request->dob) {
            $details['dob'] = [
                'label'     => 'Date of birth',
                'type'      => 'date',
                'required'  => true,
            ];
        }
        if ($request->bass_no) {
            $details['bass_no'] = [
                'label'     => 'BASS number',
                'type'      => 'text',
                'required'  => false,
            ];
        }
        if ($request->university) {
            $details['university'] = [
                'label'     => 'Education institution',
                'type'      => 'text',
                'required'  => false,
            ];
        }
        if ($request->dietary) {
            $details['dietary'] = [
                'label'     => 'Dietary Requirements',
                'type'      => 'text',
                'required'  => false,
            ];
        }
        return $details;
    }
}
