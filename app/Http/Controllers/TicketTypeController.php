<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;

class TicketTypeController extends Controller
{
    public function __construct()
    {
        // Use the App\Policies\TicketTypePolicy class to authorize
        $this->authorizeResource(TicketType::class, 'ticket_type');
    }

    public function index(Event $event): View
    {
        return view('tickets.index', [
            'event'   => $event,
            'tickets' => $event->tickets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event): View
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
    public function store(Event $event, StoreTicketRequest $request): RedirectResponse
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
            'status' => 'Ticket created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, TicketType $ticket_type): View
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
    public function edit(Event $event, TicketType $ticket_type): View
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
    public function update(UpdateTicketRequest $request, Event $event, TicketType $ticket_type): RedirectResponse
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
            'status' => 'Ticket updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, TicketType $ticket_type): RedirectResponse
    {
        if ($ticket_type->orders->count()) {
            return redirect()->back()->withErrors([
                'You cannot delete a ticket which has orders.'
            ]);
        }

        $ticket_type->delete();

        return redirect()->route('events.show', $event)->with([
            'warning' => 'Ticket deleted.',
        ]);
    }

    /**
     * Compute details for information collection on ticket.
     */
    public function computeDetails(FormRequest $request): array
    {
        $details = [];
        if ($request->dob) {
            $details['dob'] = [
                'label'     => 'Date of birth',
                'type'      => 'date',
                'required'  => true,
                'readonly'  => false,
            ];
        }
        if ($request->yob) {
            $details['yob'] = [
                'label'     => 'Year of birth',
                'type'      => 'number',
                'required'  => true,
                'readonly'  => false,
                'max'       => Carbon::now()->month >= 5 ? Carbon::now()->year - 6 : Carbon::now()->year - 5,
            ];
        }
        if ($request->gender) {
            $details['gender'] = [
                'label'     => 'Gender',
                'type'      => 'select',
                'required'  => true,
                'readonly'  => false,
                'options'   => [
                    'female'    => 'Female',
                    'male'      => 'Male',
                ],
            ];
        }
        if ($request->gbr_no) {
            $details['gbr_no'] = [
                'label'     => 'GBR registration number',
                'type'      => 'text',
                'required'  => false,
                'readonly'  => true,
                'tooltip'   => 'If you have a registration number, you must search for your competitor profile above.',
            ];
        }
        if ($request->club) {
            $details['club'] = [
                'label'     => 'Club',
                'type'      => 'text',
                'required'  => true,
                'readonly'  => false,
            ];
        }
        if ($request->university) {
            $details['university'] = [
                'label'     => 'Education institution',
                'type'      => 'text',
                'required'  => false,
                'readonly'  => false,
            ];
        }
        if ($request->dietary) {
            $details['dietary'] = [
                'label'     => 'Dietary Requirements',
                'type'      => 'text',
                'required'  => false,
                'readonly'  => false,
            ];
        }
        return $details;
    }
}
