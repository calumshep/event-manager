@extends('layout.app')

@section('content')

    <h1>Your Orders</h1>

    <table class="table table-hover table-striped border shadow">
        <thead class="thead-dark">
            <tr>
                <th>Order ID</th>
                <th>Event</th>
                <th>Tickets</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @forelse($orders as $order)
                <tr style="transform: rotate(0);">
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->tickets->first()->event->name }}</td>
                    <td>{{ $order->tickets->count() }}</td>
                    <td><a href="{{ route('orders.show', $order) }}" class="stretched-link">View &raquo;</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $orders->links() }}

@endsection
