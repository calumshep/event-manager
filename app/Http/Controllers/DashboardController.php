<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Date;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('dashboard',[
            'events' => Event::where('start', '>=', Date::now())->oldest('start')->paginate(10),
        ]);
    }
}
