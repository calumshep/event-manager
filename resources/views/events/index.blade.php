@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline mb-3">
        <h1>My Events</h1>

        <a href="{{ route('events.create')  }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>New Event
        </a>
    </div>

    <table class="table table-hover table-striped border shadow">
        <thead class="table-light">
            <tr>
                <td>Name</td>
                <td>Start</td>
                <td>End</td>
                <td></td>
            </tr>
        </thead>

        <tbody>
            @forelse($events as $event)
                <tr>
                    <td>
                        <a href="{{ route('events.show', $event) }}">
                             {{ $event->name }}
                        </a>
                    </td>
                    <td>{{ $event->start->format('D j M Y') }}</td>
                    <td>{{ $event->start->format('D j M Y') }}</td>
                    <td>
                        <a href="{{ route('events.show', $event) }}">
                            View &raquo;
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No events to display.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


@endsection
