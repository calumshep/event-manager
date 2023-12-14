@extends('layout.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline mb-3">
        <h1>My Events</h1>

        <a href="{{ route('events.create')  }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>New Event
        </a>
    </div>

    <p>
        You can run your own events. They will appear on the homepage and in searches for events.
    </p>
    <p>
        <strong>Free events will always be free.</strong> For paid events, we charge 3% for handling payments through
        Stripe.
    </p>

    @if($events->count() == 0)
        <p>
            First time running an event with us? Check out our <a href="{{ route('help.index') }}">help section</a>.
        </p>
    @endif

    @include('components.status')

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 mb-3">
        @forelse($events as $event)
            <div class="col">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h2 class="h3 card-title">{{ $event->name }}</h2>
                        <h3 class="h5 card-subtitle">
                            {{ $event->start->format('D j M Y') }}
                            {{ $event->end ? '- ' . $event->end->format('D j M Y') : '' }}
                        </h3>
                        <p class="card-text">{{ $event->short_desc }}</p>
                        <div class="d-flex align-items-end">
                            <a class="btn btn-primary btn-sm" href="{{ route('events.show', $event) }}">
                                View &raquo;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No events found.</p>
        @endforelse
    </div>

    {{ $events->links() }}

@endsection
