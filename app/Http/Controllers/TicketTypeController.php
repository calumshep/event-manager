<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Foundation\Http\FormRequest;

class TicketTypeController extends Controller
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

        $ticket_type = new TicketType([
            'name'          => $input['name'],
            'description'   => clean($request->description),
            'time'          => $request->time,
            'price'         => $input['price']*100,
        ]);

        $ticket_type->details = $this->computeDetails($request);

        $event->tickets()->save($ticket_type);

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
            'name'          => $input['name'],
            'description'   => clean($request->description),
            'time'          => $request->time,
            'price'         => $input['price']*100,
        ]);

        $ticket_type->details = $this->computeDetails($request);

        $ticket_type->save();

        return redirect()->route('events.tickets.show', [$event, $ticket_type])->with([
            'status' => 'TicketType details updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, TicketType $ticket_type)
    {
        // TODO: add checks

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
                'label' => 'Date of birth',
                'type' => 'date',
                'required' => true,
            ];
        }
        if ($request->bass_no) {
            $details['bass_no'] = [
                'label' => 'BASS number',
                'type' => 'text',
                'required' => false,
            ];
        }
        if ($request->university) {
            $details['university'] = [
                'label' => 'Education institution',
                'type' => 'text',
                'required' => false,
            ];
        }
        return $details;
    }
}
