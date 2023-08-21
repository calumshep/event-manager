<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Date;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function dashboard()
    {
        return view('dashboard', [
            'events' => Event::where('start', '>=', Date::now())->oldest('start')->paginate(10),
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
        return view('events.detail', compact('event'));
    }
}
