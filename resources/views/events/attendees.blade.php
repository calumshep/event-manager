@extends('layout.app')

@section('head')
    <link href="https://cdn.jsdelivr.net/gh/tofsjonas/sortable@latest/sortable.min.css" rel="stylesheet">
@endsection

@section('content')

    <div class="d-flex justify-content-between">
        <div>
            <h1>Attendee List</h1>
            <h2 class="h3">{{ $event->name }}</h2>
        </div>

        <div>
            <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">&laquo; Back to Event</a>
        </div>
    </div>

    <div class="d-flex">
        <a href="{{ route('events.sales', $event) }}" class="btn btn-success me-2">
            <i class="fa-solid fa-sterling-sign me-2"></i>Ticket Sales
        </a>

        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <i class="fa-solid fa-file-export me-2"></i>Download Attendee List
            </button>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item"
                       href="{{ route('events.attendees.export', $event) }}"
                       target="_blank">
                    <i class="fa-solid fa-file-csv me-2"></i>CSV file
                </a></li>
            </ul>
        </div>
    </div>

    <table class="sortable table table-hover table-striped w-100 rounded shadow">
        <thead>
            <tr>
                <th>Ordered At</th>
                <th>Order ID</th>
                <th>Order Email</th>
                <th>Ticket Type</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>{{ $event->isRace()
                    ? 'YOB'
                    : 'Special Requests'
                }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->updated_at->format('H:i:s d/m/Y') }}</td>
                    <td>{{ $ticket->order->id }}</td>
                    <td>{{ $ticket->order->orderable->email }}</td>
                    <td>{{ $ticket->ticketType->name }}</td>
                    <td>{{ $ticket->first_name }}</td>
                    <td>{{ $ticket->last_name }}</td>
                    <td>
                        @if($event->isRace())
                            {{ $ticket->metadata['yob'] }}
                        @elseif($ticket->order->special_requests)
                            <i class="fa-solid fa-circle-exclamation"
                               data-bs-toggle="tooltip"
                               data-bs-placement="right"
                               data-bs-title="{{ $ticket->order->special_requests }}"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/gh/tofsjonas/sortable@latest/sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/tofsjonas/sortable@latest/sortable.a11y.min.js"></script>
    <script>
        window.addEventListener('load', function()
        {
            document.querySelector('td').click();
        });
    </script>
@endsection
