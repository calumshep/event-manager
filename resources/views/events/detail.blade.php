@extends('layouts.app')

@section('content')

    <h1>{{ $event->name }}</h1>
    <h2 class="h4 text-muted">
        {{ $event->start->format('D j M Y') }} {{ isset($event->end) ? 'to ' . $event->end->format('D j M Y') : null }}
    </h2>
    <p>{{ $event->short_desc }}</p>

    <hr>

    <p>{{ $event->long_desc }}</p>

    <hr>

    <h2 class="h3">Get Tickets</h2>

    <form method="POST" action="{{ route('events.tickets.checkout', $event) }}">
        @csrf

        <div class="row row-cols-2 row-cols-lg-3 mb-3">
            @forelse($event->tickets as $ticket)
                <div class="col">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h3 class="h5 card-title">{{ $ticket->name }}</h3>
                            <h4 class="h6 card-subtitle">
                                {{ $ticket->time->format('D j M Y') }}
                                &middot;
                                £{{ number_format($ticket->price/100, 2) }}
                            </h4>

                            <p class="card-text">{{ $ticket->description }}</p>

                            <div class="card-text form-floating">
                                <input type="number"
                                       min="0"
                                       name="quantity_{{ $ticket->id }}"
                                       id="quantity_{{ $ticket->id }}"
                                       value="{{ old('quantity') ? old('quantity') : 0 }}"
                                       class="form-control">
                                <label for="quantity_{{ $ticket->id }}">Quantity</label>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">
                    No tickets are available.
                </div>
            @endforelse
        </div>

        @unless(empty($event->tickets))
            <button type="submit" class="btn btn-primary float-end">
                Checkout &raquo;
            </button>
        @endunless
    </form>

@endsection
