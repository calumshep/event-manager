<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Date;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function dashboard(): View
    {
        return view('welcome', [
            'events' => Event::where('start', '>=', Date::now())
                             ->oldest('start')
                             ->paginate(5),
        ]);
    }

    /**
     * Display the detailed view of an event.
     */
    public function event(Event $event): View
    {
        return view('events.detail', [
            'event'     => $event,
        ]);
    }
}
