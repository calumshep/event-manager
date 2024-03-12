<x-mail::message>
# Your Tickets
## Order #{{ $order->id }}

Thank you for your recent order for the {{ $event->name }} event.

<hr>

## Order Summary

<x-mail::table>
| Ticket | Price | Quantity | Subtotal |
| :--- | :--- | :---: | ---: |
@foreach($order->tickets->groupBy('id') as $ticket_type)
| {{ $ticket_type->first()->name }} | £{{ number_format($ticket_type->first()->price / 100, 2) }} | {{
$ticket_type->count() }} | £{{ number_format(($ticket_type->first()->price*$ticket_type->count()) / 100, 2) }} |
@endforeach
</x-mail::table>

### Total: £{{ number_format($order->total_amount/100, 2) }}

### Completed at: {{ $order->updated_at->format('H:i d/m/Y') }}

<x-mail::button :url="$url">
View Order
</x-mail::button>

<hr>

We look forward to seeing you there!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
