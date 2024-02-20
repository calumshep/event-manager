<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;

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
            'event'             => $event,
            'orders'            => $event->getOrders()->paginate(5),
            // Get ordered tickets grouped by type to obtain a count of how many of each ticket type are sold
            'sales_progress'    =>
                $event->getTickets()
                    ->groupBy('ticket_type_id')
                    ->sortBy('updated_at'),
        ]);
    }

    /**
     * Export event sales to a CSV file and download to the browser.
     *
     * @param \App\Models\Event $event
     *
     * @return void
     */
    public function exportSales(Event $event)
    {
        $this->prepareFile(
            filename: 'event_' . $event->id . '_sales_' . now()->toDateTimeLocalString() . '.csv',
            headers: [
                'id', 'checkout_id', 'total_amount_pence', 'paid', 'email', 'created_at', 'updated_at', 'num_tickets',
            ],
            data: $event->getOrders()->map(function (Order $o) {
                return [
                    $o->id,
                    $o->checkout_id,
                    $o->total_amount,
                    $o->paid,
                    $o->orderable->email,
                    $o->created_at->toDateTimeLocalString(),
                    $o->updated_at->toDateTimeLocalString(),
                    $o->tickets->count(),
                ];
            })->toArray()
        );
    }

    /**
     * Show the attendee list view.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function attendees(Event $event)
    {
        return view('events.attendees', [
            'event'     => $event,
            'tickets'   => $event->getTickets()
        ]);
    }

    public function exportAttendees(Event $event)
    {
        $this->prepareFile(filename: 'event_' . $event->id . '_attendees_' . now()->toDateTimeLocalString() . '.csv',
            headers: [
                'ticket_id', 'order_id', 'order_email', 'ticket_type_id', 'ticket_type', 'ticket_holder_name',
                'ticket_data', 'created_at', 'updated_at',
            ],
            data: $event->getTickets()->map(function (OrderTicket $t) {
                return [
                    $t->id,
                    $t->order_id,
                    $t->order->orderable->email,
                    $t->ticket_type_id,
                    $t->ticketType->name,
                    $t->ticket_holder_name,
                    $t->metadata ? $t->metadata->toString() : '',
                    $t->created_at->toDateTimeLocalString(),
                    $t->updated_at->toDateTimeLocalString(),
                ];
            })->toArray()
        );
    }

    public function prepareFile(string $filename, array $headers, array $data)
    {
        ob_start();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        ob_end_clean();

        $output = fopen('php://output', 'w');

        fputcsv($output, $headers);
        foreach($data as $item) {
            fputcsv($output, $item);
        }

        fclose($output);
    }
}
