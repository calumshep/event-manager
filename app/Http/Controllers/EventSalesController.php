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
        // Get ticket types relevant to the event
        $possible_ticket_types = TicketType::whereEventId($event->id)->get();

        // Get tickets which have been ordered
        $ordered_tickets = OrderTicket::whereIn(
            'ticket_type_id',
            $possible_ticket_types->pluck('id')
        )->get();

        // Group tickets into orders
        $orders = $ordered_tickets
                ->load('ticketType')
                ->groupBy('order_id');

        return view('events.sales', [
            'event'             => $event,

            // Map the orders into a neat package of Order instances which contain Tickets.
            'orders'            =>
                $orders->map(function (Collection $order)
                {
                    $collated_order = $order->first()->order;
                    $collated_order->tickets = $order;

                    return $collated_order;
                })->sortByDesc('updated_at')
                ->paginate(5),

            // Get ordered tickets grouped by type to obtain a count of how many of each ticket type are sold
            'sales_progress'    =>
                $ordered_tickets
                    ->groupBy('ticket_type_id')
                    ->sortBy('updated_at'),
        ]);
    }
}
