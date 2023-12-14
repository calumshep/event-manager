@extends('layout.app')

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

    <form method="POST" action="{{ route('events.tickets.purchase', $event) }}">
        @csrf

        <div class="row row-cols-2 g-4">
            @forelse($event->tickets as $ticket)
                <div class="col">
                    <div class="card shadow mb-3">
                        <div class="card-body">
                            <h3 class="h5 card-title">{{ $ticket->name }}</h3>
                            <h4 class="h6 card-subtitle">
                                {{ $ticket->time->format('D j M Y') }}
                                &middot;
                                £{{ number_format($ticket->price/100, 2) }}
                            </h4>

                            <hr>
                            <p>{{ $ticket->description }}</p>
                            <hr>
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
            <div class="row">
                <div class="col-12 col-md-6 ms-auto">
                    <div class="d-flex justify-content-between align-items-baseline border-top border-bottom pt-3 mb-3">
                        <h4>Total</h4>
                        <p><span id="total_amount">£0.00</span></p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary float-end">
                Checkout &raquo;
            </button>
        @endunless
    </form>

@endsection

@section('scripts')
    <script type="text/javascript" defer>
        // Price formatter
        let GBP = new Intl.NumberFormat('en-GB', {
            style: 'currency',
            currency: 'GBP',
        });

        /**
         * Calculate the total price based on the current state.
         * @returns {number} Total price (in pounds).
         */
        function calcTot() {
            let total = 0;

            document.querySelectorAll('[id^="price_"]').forEach((price) => {
                total += parseInt(price.value) * parseInt(price.nextElementSibling.value);
            });

            return total / 100;
        }
    </script>
@endsection
