<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct()
    {
        // Use the App\Policies\EventPolicy class to authorize
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('events.index', [
            'events' => auth()->user()->events->sortBy('start')->paginate(6),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('events.form', [
            'event'         => new Event(),
            'orgs'          => auth()->user()->organisations,
            'readonly'      => false,
            'creating'      => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $input = $request->validated();

        $event = auth()->user()->events()->create([
            'name'              => $input['name'],
            'start'             => $input['start'],
            'type'              => $input['type'],
            'end'               => $input['end'],
            'slug'              => Str::of($input['name'])->slug(),
            'short_desc'        => $input['short_desc'],
            'long_desc'         => clean($request->long_desc),
            'organisation_id'   => $input['org'],
        ]);

        return redirect()->route('events.show', $event)->with([
            'status' => 'Event created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        return view('events.form', [
            'event'         => $event,
            'orgs'          => $event->user->organisations,
            'readonly'      => true,
            'creating'      => false,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        return view('events.form', [
            'event'         => $event,
            'orgs'          => $event->user->organisations,
            'readonly'      => false,
            'creating'      => false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $input = $request->validated();

        $event = $event->fill([
            'name'              => $input['name'],
            'start'             => $input['start'],
            'type'              => $input['type'],
            'end'               => $input['end'],
            'slug'              => Str::of($input['name'])->slug(),
            'short_desc'        => $input['short_desc'],
            'long_desc'         => clean($request->long_desc),
            'organisation_id'   => $input['org'],
        ]);
        $event->save();

        return redirect()->route('events.show', $event)->with([
            'status' => 'Event updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        if ($event->tickets->count()) {
            return redirect()->back()->withErrors([
                'You cannot delete an event which contains tickets. Try deleting the tickets first.',
            ]);
        }

        $event->delete();

        return redirect()->route('events.index')->with([
            'warning' => 'Event deleted.',
        ]);
    }
}
