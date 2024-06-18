@extends('layout.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline mb-3">
        <h1>My Events</h1>

        <a href="{{ route('events.create')  }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>New Event
        </a>
    </div>

    <p>
        Shown below are all the events you have created, and events belonging to organisations which you are a member
        of.
    </p>

    <hr>

    {{-- @if($events->count() == 0)
        <p>
            First time running an event with us? Check out our <a href="{{ route('help.index') }}">help section</a>.
        </p>
    @endif --}}

    @include('components.status')

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 mb-3">
        @forelse($events as $event)
            <div class="col">
                <div class="card h-100 shadow">
                    <div class="card-body" style="transform: rotate(0);">
                        <h2 class="h3 card-title">
                            <a href="{{ route('events.show', $event) }}" class="stretched-link text-decoration-none">
                                {{ $event->name }}
                            </a>
                        </h2>

                        <h3 class="h5 card-subtitle">
                            {{ $event->start->format('D j M Y') }}
                            {{ $event->end ? '- ' . $event->end->format('D j M Y') : '' }}
                        </h3>

                        <p class="card-text">{{ $event->short_desc }}</p>
                    </div>

                    <ul class="list-group list-group-flush">
                        <a href="{{ route('events.show', $event) }}"
                           class="list-group-item list-group-item-action list-group-item-primary">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>Details
                        </a>

                        <a href="{{ route('events.sales', $event) }}"
                           class="list-group-item list-group-item-action list-group-item-success">
                            <i class="fa-solid fa-sterling-sign me-2"></i>Ticket Sales
                        </a>
                    </ul>
                </div>
            </div>
        @empty
            <p class="lead">
                No events found. Maybe you want to <a href="{{ route('events.create') }}">create one</a>?
            </p>
        @endforelse
    </div>

    {{ $events->links() }}

@endsection
