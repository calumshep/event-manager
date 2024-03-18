<x-mail::message>
# Your Tickets
## Order #{{ $order->id }}

Thank you for your recent order for the SSC Snow Ball and thanks for supporting your Club! Your tickets are attached to
this email.

The ticket price covers the costs of the Ball. Check out our SSC Bursary fundraising video below. We will be raising
money on the night for this and raffle tickets will be available to purchase to support it.  Please reply to this email
if you have any raffle prizes to offer or sponsorship.

<a href="https://www.youtube.com/watch?v=aAa8AQalWBA">
    <img src="{{ asset('img/thumbnail.jpg') }}" alt="SSC Bursary fundraising video">
</a>

We have a special room rate of £140 for a double room for two people. There are 30 rooms set aside for the Club.
Please click the link below to book this.

<x-mail::button :url="$room_url">
Book a Room
</x-mail::button>

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

@if($order->orderable_type === 'App\Models\User')
<x-mail::button :url="$url">
View Order
</x-mail::button>
@endif

<hr>

We look forward to seeing you there!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
