@extends('layout.app')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="d-flex justify-content-between">
                <div>
                    <h1>Event Sales</h1>
                    <h2 class="h3">{{ $event->name }}</h2>
                </div>

                <div>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">&laquo; Back to Event</a>
                </div>
            </div>

            <div class="d-flex">
                <a href="{{ route('events.attendees', $event) }}" class="btn btn-success me-2">
                    <i class="fa-solid fa-people-group me-2"></i>Attendee List
                </a>

                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fa-solid fa-file-export me-2"></i>Download Orders
                    </button>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"
                               href="{{ route('events.sales.export', $event) }}"
                               target="_blank">
                            <i class="fa-solid fa-file-csv me-2"></i>CSV file
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <h3 class="h6">Sales progress</h3>
            @foreach($sales_progress as $sale)
                @if($sale->first()->ticketType->capacity)
                    <p class="mb-0">{{ $sale->first()->ticketType->name }}</p>

                    <div class="progress"
                         role="progressbar"
                         aria-label="{{ $sale->first()->name }} sales"
                         aria-valuenow="{{ ($sale->count() / $sale->first()->ticketType->capacity) * 100 }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        <div class="progress-bar overflow-visible"
                             style="width: {{ ($sale->count() / $sale->first()->ticketType->capacity) *100 }}%">
                            {{ $sale->count() }} / {{ $sale->first()->ticketType->capacity }}
                            ({{ number_format(($sale->count() / $sale->first()->ticketType->capacity) * 100, 0) }}%)
                        </div>
                    @else
                        <p class="mb-0">
                            {{ $sale->first()->ticketType->name }}
                            <span class="badge text-bg-primary">{{ $sale->count() }}</span>
                        </p>
                    @endif
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
                    <code>{{ $order->checkout_id }}</code>
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
