<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\OrderTicket;
use App\Models\TicketType;
use Illuminate\Support\Collection;

class EventSalesController extends Controller
{
    /**
     * Show the event sales view.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function sales(Event $event)
    {
        return view('events.sales', [
            'event'     => $event,
            'orders'    =>
                // Get orders containing ticket types relevant to the event.
                OrderTicket::whereIn(
                    'ticket_type_id',
                    TicketType::whereEventId($event->id)->pluck('id')
                )->get()
                // Load the related TicketType models which link to the event.
                ->load('ticketType')
                ->groupBy('order_id')

                // Map the orders into a neat package of Orders which contain Tickets.
                ->map(function (Collection $order)
                {
                    $collated_order = $order->first()->order;
                    $collated_order->tickets = $order;

                    return $collated_order;
                })->sortBy('updated_at')
                ->paginate(5),
        ]);
    }
}
