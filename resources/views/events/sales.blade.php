@extends('layout.sidebar-form')

@section('head')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('title', $event->name)

@section('sidebar')
    @include('layout.event-nav')
@endsection

@section('form')
    <h2 class="h3">Sales progress</h2>

    {{-- Sales progres --}}
    <div class="row row-cols-4">
        @foreach($sales_progress as $sale)
            <div class="col">
                <div class="card shadow mb-3">
                    <div class="card-body">
                        <h3 class="h5 card-title">{{ $sale->first()->ticketType->name }}</h3>

                        @if($sale->first()->ticketType->capacity)
                            <div class="progress"
                                 role="progressbar"
                                 aria-label="{{ $sale->first()->name }} sales"
                                 aria-valuenow="{{ ($sale->count() / $sale->first()->ticketType->capacity) * 100 }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                <div class="progress-bar overflow-visible"
                                     style="width: {{ ($sale->count() / $sale->first()->ticketType->capacity) * 100 }}%">
                                    {{ number_format(($sale->count() / $sale->first()->ticketType->capacity) * 100) }}%
                                </div>
                            </div>
                        @endif

                        <div class="text-center mx-auto">
                            <span class="fs-5 mt-2 badge text-bg-primary">
                                {{ $sale->first()->ticketType->capacity
                                   ? $sale->count() . ' / ' . $sale->first()->ticketType->capacity
                                   : $sale->count() . ' sold' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <hr>

    {{-- Order list --}}
    <div class="d-flex justify-content-between align-items-baseline mb-2">
        <h2 class="h3">Recent Orders</h2>

        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <i class="fa-solid fa-file-export me-2"></i>Download Orders
            </button>

            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item"
                       href="{{ route('events.sales.export', $event) }}"
                       target="_blank">
                        <i class="fa-solid fa-file-csv me-2"></i>CSV file
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @forelse($orders as $order)
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4 class="h6 d-flex justify-content-between mb-0">
                    <span>Order #{{ $order->id }} &middot; <code>{{ $order->checkout_id }}</code></span>
                    <span class="badge text-bg-success fs-6">£{{ number_format($order->total_amount/100, 2) }} paid</span>
                </h4>

                <p class="fw-light">
                    Ordered
                    <span class="fw-lighter"
                          data-bs-toggle="tooltip"
                          data-bs-title="{{ $order->updated_at->format('H:i, D j M Y') }}">
                        <i class="fa-solid fa-clock fa-xs"></i>
                        {{ $order->updated_at->diffForHumans() }}
                    </span>
                    by <a href="mailto:{{ $order->orderable->email }}">{{ $order->orderable->email }}</a>
                </p>

                <div class="list-group">
                    @foreach($order->tickets as $ticket)
                        <button type="button" class="list-group-item list-group-item-action">
                            <span class="h6">{{ $ticket->ticketType->name }}</span>
                            &middot;
                            {{ $ticket->first_name
                               ? $ticket->first_name . ' ' . $ticket->last_name
                               : $ticket->ticket_holder_name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <p class="lead">Looks like you haven't sold any tickets yet.</p>
    @endforelse

    {{ $orders->links() }}

@endsection
