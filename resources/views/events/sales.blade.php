@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <h1>Event Sales</h1>
            <h2 class="h3">{{ $event->name }}</h2>
        </div>

        <div class="col-lg-6">
            <h3 class="h6">Sales progress</h3>
            @foreach($event->tickets as $ticket_type)
                <p class="mb-0">{{ $ticket_type->name }}</p>
                <div class="progress"
                     role="progressbar"
                     aria-label="{{ $ticket_type->name }} sales"
                     aria-valuenow="33"
                     aria-valuemin="0"
                     aria-valuemax="100">
                    <div class="progress-bar" style="width: 33%">20/60 (33%)</div>
                </div>
            @endforeach
        </div>
    </div>

    <hr>

    <h3 class="h5">Recent Orders</h3>

    @forelse($orders as $order)

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4 class="h6">
                    Order {{ $order->id }}
                    &middot;
                    {{ $order->checkout_id }}
                </h4>

                <p class="fw-ligther">
                    {{ $order->orderable->email }}
                    &middot;
                    {{ $order->updated_at->format('H:i d/m/Y') }}
                    &middot;
                    £{{ number_format($order->total_amount/100, 2) }}
                </p>

                <div class="list-group">
                    @foreach($order->tickets as $ticket)
                        <a href="#" class="list-group-item list-group-item-action">
                            <span class="h6">{{ $ticket->ticketType->name }}</span>
                            &middot;
                            {{ $ticket->ticket_holder_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    @empty
        <p class="lead">Looks like you haven't sold any tickets yet.</p>
    @endforelse

    {{ $orders->links() }}

@endsection
