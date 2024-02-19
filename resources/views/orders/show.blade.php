@extends('layout.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        <h1>Your Order <span class="text-muted">#{{ $order->id }}</span></h1>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary">&laquo; Back</a>
    </div>

    <h2 class="h3">
        {{ $event->name }}
        <small class="h6">
            &middot;
            <a href="{{ route('home.event', $event) }}">View &raquo;</a>
        </small>
    </h2>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <h3 class="h5">Order Summary</h3>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
                </thead>

                <tbody>
                @foreach($order->tickets->groupBy('id') as $ticket_type)
                    <tr>
                        <td>{{ $ticket_type->first()->name }}</td>
                        <td>£{{ number_format($ticket_type->first()->price / 100, 2) }}</td>
                        <td>{{ $ticket_type->count() }}</td>
                        <td>£{{ number_format(($ticket_type->first()->price*$ticket_type->count()) / 100, 2) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th>£{{ number_format($order->total_amount/100, 2) }}</th>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h3 class="h5">Download Tickets</h3>

            <div class="list-group">
                @foreach($order->tickets as $ticket)
                    <a href="#" class="list-group-item list-group-item-action">
                        <span class="h6">{{ $ticket->name }}</span>
                        &middot;
                        {{ $ticket->data->ticket_holder_name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@endsection
