<x-mail::message>
# New Order
## {{ $event->name }}
### Order #{{ $order->id }}

A new order has come in for the event, run by {{ $event->organisation->name }},
which you're a member of. Find the order summary below.

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

<hr>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
