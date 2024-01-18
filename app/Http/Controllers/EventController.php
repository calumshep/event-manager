<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('events.index', [
            'events' => auth()->user()->events->sortBy('start')->paginate(6),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function store(StoreEventRequest $request)
    {
        $input = $request->validated();

        $event = new Event([
            'name'              => $input['name'],
            'start'             => $input['start'],
            'end'               => $input['end'],
            'slug'              => Str::of($input['name'])->slug(),
            'short_desc'        => $input['short_desc'],
            'long_desc'         => clean($request->long_desc),
            'organisation_id'   => $input['org'],
        ]);

        auth()->user()->events()->save($event);

        return redirect()->route('events.show', $event)->with([
            'status' => 'Event successfully created.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
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
    public function edit(Event $event)
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
    public function update(UpdateEventRequest $request, Event $event)
    {
        $input = $request->validated();

        $event = $event->fill([
            'name'              => $input['name'],
            'start'             => $input['start'],
            'end'               => $input['end'],
            'slug'              => Str::of($input['name'])->slug(),
            'short_desc'        => $input['short_desc'],
            'long_desc'         => clean($request->long_desc),
            'organisation_id'   => $input['org'],
        ]);

        $event->save();

        return redirect()->route('events.show', $event)->with([
            'status' => 'Event successfully updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with([
            'warning' => 'Event deleted.'
        ]);
    }
}
