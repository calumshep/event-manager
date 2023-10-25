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

    @foreach($tickets as $ticket)
        <div class="card shadow mb-3">
            <div class="card-body">
                <h3 class="h5 card-title">{{ $ticket->name }}</h3>
                <h4 class="h6 card-subtitle">
                    {{ $ticket->time->format('D j M Y') }}
                    &middot;
                    £{{ number_format($ticket->price/100, 2) }}
                </h4>

                @for($i = 0; $i < $ticket->quantity; $i++)
                    <p>{{ $i }}</p>
                @endfor
            </div>
        </div>
    @endforeach

@endsection
