<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Date;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function welcome()
    {
        return view('welcome', [
            'events' => Event::where('start', '>=', Date::now())
                             ->oldest('start')
                             ->paginate(5),
        ]);
    }

    /**
     * Display the detailed view of an event.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function event(Event $event)
    {
        return view('events.detail', [
            'event'     => $event,
        ]);
    }
}
