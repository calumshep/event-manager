@extends('layout.app')

@section('head')
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.0/datatables.min.css" rel="stylesheet">
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

    <table id="attendee-table" class="table table-striped table-hover rounded shadow">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Ticket Type</th>
                <th>Attendee Name</th>
                <th>Order ID</th>
                <th>Order Email</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->ticketType->name }}</td>
                    <td>{{ $ticket->ticket_holder_name }}</td>
                    <td>{{ $ticket->order->id }}</td>
                    <td>{{ $ticket->order->orderable->email }}</td>
                    <td>{{ $ticket->created_at->format('H:i:s d/m/Y') }}</td>
                    <td>{{ $ticket->updated_at->format('H:i:s d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.0/datatables.min.js"></script>
    <script>
        let table = new DataTable('#attendee-table')
    </script>
@endsection
