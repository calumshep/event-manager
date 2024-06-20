@extends('layout.app')

@section('content')

    @include('components.status')

    @include('components.event-detail-header')

    <p>
        Specify how many {{ $event->isRace() ? 'entries' : 'of each ticket type' }} you want to purchase. You can enter
        further details on the next page.
    </p>

    <form method="POST" action="{{ route('event.tickets.checkout', $event) }}">
        @csrf

        <div class="row row-cols-md-2 row-cols-1 g-4">
            @forelse($event->tickets as $ticket)
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm">
                                    <h3 class="h5 card-title">{{ $ticket->name }}</h3>
                                    <h4 class="h6 card-subtitle">
                                        {{ $ticket->time->format('D j M Y') }}
                                        &middot;
                                        £{{ number_format($ticket->price/100, 2) }}
                                    </h4>
                                </div>
                                @if($ticket->capacity && $ticket->show_remaining)
                                    <div class="col-sm-auto">
                                        <span class="badge text-bg-primary">
                                            {{ $ticket->remaining() }} / {{ $ticket->capacity }} tickets left
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <hr>
                            <p>{!! $ticket->description !!}</p>

                            <hr>
                            <input type="hidden" id="price_{{ $ticket->id }}" value="{{ $ticket->price }}">
                            <div class="input-group">
                                <span class="input-group-text" id="quantity_{{ $ticket->id }}_label">Quantity</span>
                                <input type="number"
                                       min="0"
                                       @if($ticket->capacity) max="{{ $ticket->remaining() }}" @endif
                                       step="1"
                                       name="quantity_{{ $ticket->id }}"
                                       id="quantity_{{ $ticket->id }}"
                                       class="form-control"
                                       placeholder="0"
                                       aria-label="Quantity"
                                       aria-describedby="quantity_{{ $ticket->id }}_label">
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
            <div class="row border-top border-bottom pt-3 my-3">
                <div class="col-12 col-md-6 mx-auto">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h4>Total</h4>
                        <p><span id="total_amount">£0.00</span></p>
                    </div>
                </div>
            </div>

            <div class="mx-auto text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    Continue &raquo;
                </button>
            </div>
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
        function calcTot()
        {
            let total = 0;

            document.querySelectorAll('[id^="price_"]').forEach((priceInput) => {
                let quantity = priceInput.nextElementSibling.lastElementChild.value
                if (quantity) {
                    total += parseInt(priceInput.value) * parseInt(quantity);
                }
            });

            return total / 100;
        }

        /**
         * Update the price display.
         */
        function updateTot()
        {
            document.querySelector('#total_amount').innerHTML = GBP.format(calcTot());
        }

        document.querySelectorAll('input[id^="quantity_"]').forEach((quantityInput) => {
            quantityInput.addEventListener('change', updateTot);
        });
    </script>
@endsection
