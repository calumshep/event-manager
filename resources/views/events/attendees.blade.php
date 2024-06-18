@extends('layout.sidebar-form')

@section('head')
    <link href="https://cdn.jsdelivr.net/gh/tofsjonas/sortable@latest/sortable.min.css" rel="stylesheet">
@endsection

@section('title', $event->name)

@section('sidebar')
    @include('layout.event-nav')
@endsection

@section('form')
    <div class="d-flex justify-content-between mb-2">
        <h2 class="h3">Attendee List</h2>

        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <i class="fa-solid fa-file-export me-2"></i>Download Attendee List
            </button>

            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item"
                       href="{{ route('events.attendees.export', $event) }}"
                       target="_blank">
                        <i class="fa-solid fa-file-csv me-2"></i>CSV file
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        {{-- Ticket Type filter --}}
        <div class="col-sm mb-2">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        data-bs-aut-close="outside">
                    <i class="fa-solid fa-filter me-2"></i>
                    Filter by Ticket Type
                </button>

                <form class="dropdown-menu p-3" method="GET">
                    @foreach($event->tickets as $ticket)
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       value="{{ $ticket->id }}"
                                       id="ticket_{{ $ticket->id }}"
                                    @checked(true)>
                                <label class="form-check-label" for="ticket_{{ $ticket->id }}">
                                    {{ $ticket->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-rotate me-2"></i>
                        Update
                    </button>
                </form>
            </div>
        </div>

        {{-- Attendee/order search --}}
        <div class="col-sm mb-2">
            <form method="GET">
                <label class="visually-hidden" for="attendee-search">Search attendees</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="attendee-search" placeholder="Search attendees...">

                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa-solid fa-search"></i>
                        <span class="visually-hidden">Search</span>
                    </button>
                </div>
            </form>
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
