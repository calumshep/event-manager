<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('events.index', [
            'events' => auth()->user()->events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.form', [
            'event'         => new Event(),
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
            'name'          => $input['name'],
            'start'         => $input['start'],
            'end'           => $input['end'],
            'short_desc'    => $input['short_desc'],
            'long_desc'     => $input['long_desc'],
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
            'name'          => $input['name'],
            'start'         => $input['start'],
            'end'           => $input['end'],
            'short_desc'    => $input['short_desc'],
            'long_desc'     => $input['long_desc'],
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
        //
    }
}
