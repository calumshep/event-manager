<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use Illuminate\Contracts\View\View;

class EventSalesController extends Controller
{
    /**
     * Show the event sales view.
     */
    public function sales(Event $event): View
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
     */
    public function exportSales(Event $event): void
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
     */
    public function attendees(Event $event): View
    {
        return view('events.attendees', [
            'event'     => $event,
            'tickets'   => $event->getTickets()
        ]);
    }

    /**
     * Export attendee list to a CSV file and download to the browser.
     */
    public function exportAttendees(Event $event): void
    {
        $headers = [
            'ticket_id',
            'order_id',
            'last_updated',
            'order_email',
            'order_phone',
            'special_requests',
            'ticket_type_id',
            'ticket_type',
            'first_name',
            'last_name',
        ];

        // Get all possible metadata headers
        $metadata_headers = [];
        foreach ($event->getTickets() as $ticket) {
            if ($ticket->details) {
                foreach ($ticket->details as $detail) {
                    $metadata_headers[] = array_search($detail, $ticket->details);
                }
            }
        }


        $data = $event->getTickets()->map(function (OrderTicket $t) {
            $data = [
                $t->id,
                $t->order_id,
                $t->updated_at->toDateTimeLocalString(),
                $t->order->orderable->email,
                $t->order->orderable->phone_number,
                $t->order->special_requests,
                $t->ticket_type_id,
                $t->ticketType->name,
                $t->first_name,
                $t->last_name,
            ];

            foreach ($t->metadata as $metadatum) {
                $data[array_search($metadatum, $t->metadata)] = $metadatum;
            }

            return $data;
        })->toArray();

        $this->prepareFile(filename: 'event_' . $event->id . '_attendees_' . now()->toDateTimeLocalString() . '.csv',
            headers: array_merge($headers, $metadata_headers),
            data: $data,
        );
    }

    /**
     * Prepare a file stream for output.
     *
     * @param string $filename The name of the file to be downloaded, including extension (.csv).
     * @param array $headers Array of column headers to be used for the header row. Must be in the same order as data.
     * @param array $data 2D array of data (row-by-row).
     */
    public function prepareFile(string $filename, array $headers, array $data): void
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
