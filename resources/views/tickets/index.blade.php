@extends('layout.sidebar-form')

@section('title', $event->name)

@section('sidebar')
    @include('layout.event-nav')
@endsection

@section('form')
    <p>
        <strong>Your event needs at least one ticket type for people to register.</strong> This ticket type can be free,
        or you can set a price in GBP which must be paid to order the ticket and register.
    </p>

    <hr>

    <div class="row pt-3 mb-3">
        <div class="col-lg-4">
            <h2 class="h5">Tickets</h2>

            <p>
                These are the ticket types that exist for your event.
            </p>
        </div>

        <div class="col-lg-8">
            <p>
                <a href="{{ route('events.tickets.create', $event)  }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>New Ticket
                </a>
            </p>

            <table class="table table-hover table-striped border card-text">
                <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Validity</th>
                    <th>Price</th>
                    <th>Capacity</th>
                </tr>
                </thead>

                <tbody>
                @forelse($event->tickets as $ticket)
                    <tr>
                        <td><a href="{{ route('events.tickets.show', [$event, $ticket]) }}">{{
                                $ticket->name
                                }}</a></td>
                        <td>{{ $ticket->time->format('d/m/Y') }}</td>
                        <td>£{{ number_format($ticket->price/100, 2) }}</td>
                        <td>{{ $ticket->capacity ?? 'Not set' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            No tickets found for this event.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
