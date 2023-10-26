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

    <p>
        Select an entrant, then click "Enter" to get a ticket for them. Your total will be added up and you can proceed
        to the checkout at the bottom of the page.
    </p>
    <p class="text-muted">
        Not seeing any entrants? Go to <a href="{{ route('entrants.index') }}">Your Entrants</a>.
    </p>

    <form method="POST" action="{{ route('events.tickets.purchase', $event) }}">
        @csrf

        @forelse($event->tickets as $ticket)
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

                    <input type="hidden" name="quantity_{{ $ticket->id }}" value="{{ $ticket->quantity }}">

                    <div class="input-group my-3">
                        <label class="input-group-text" for="ticket_{{ $ticket->id }}_entrant">Choose entrant</label>
                        <select class="form-select entrant-select" id="ticket_{{ $ticket->id }}_entrant"></select>
                        <button type="button" class="btn btn-outline-primary entry-button">Enter &raquo;</button>
                    </div>

                    <ul class="list-group" id="ticket_{{ $ticket->id }}_entrants"></ul>
                </div>
            </div>
        @empty
            <div class="alert alert-warning">
                No tickets are available.
            </div>
        @endforelse

        @unless(empty($event->tickets))
            <div class="row">
                <div class="col-12 col-md-6 ms-auto">
                    <div class="d-flex justify-content-between align-items-baseline border-top border-bottom pt-3 mb-3">
                        <h4>Total</h4>
                        <p><span id="total_amount">£0.00</span></p>
                    </div>
                </div>
            </div>

            <input type="hidden" name="total_amount" value="">

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
        function calcTot()
        {
            let total = 0;

            document.querySelectorAll('[id^="price_"]').forEach((price) =>
            {
                total += parseInt(price.value) * parseInt(price.nextElementSibling.value);
            });

            return total/100;
        }

        /**
         * Fill out all selects' options with an array of entrants.
         * @param entrants JSON serialized entrant collection
         */
        function updateSelects(entrants)
        {
            entrants.forEach((entrant) => {
                document.querySelectorAll('.entrant-select').forEach((select) => {
                    let opt = document.createElement('option')
                    opt.value = entrant.id;
                    opt.id = entrant.id;
                    opt.innerHTML = entrant.name;
                    select.options.add(opt);
                });
            });
        }

        /**
         * Disable the specified entrant option in all selects.
         * @param entrant_id ID of the entrant to remove
         */
        function disableEntrant(entrant_id)
        {

            document.querySelectorAll('option').forEach((opt) => {
                if (opt.value === entrant_id) {
                    opt.disabled = true;
                }
            });
        }

        /**
         * Enable the specified entrant option in all selects.
         * @param entrant_id ID of the entrant to remove
         */
        function enableEntrant(entrant_id)
        {
            document.querySelectorAll('option').forEach((opt) => {
                if (opt.value === entrant_id) {
                    opt.disabled = false;
                }
            });
        }

        /**
         * Add an entrant item (<li>) to the specified ticket
         *
         * @param ticket_id ID of the ticket to add the entrant to
         * @param entrant_id ID of the entrant
         * @param entrant_name Full name of the entrant
         */
        function addEntrantItem(ticket_id, entrant_id, entrant_name)
        {
            let item = document.createElement('li');
            item.className = 'list-group-item px-2 d-flex justify-content-between align-items-baseline';

            let namespan = document.createElement('span');
            namespan.innerHTML = entrant_name;
            item.appendChild(namespan);

            let btn = document.createElement('button');
            btn.role = 'button';
            btn.className = 'btn btn-sm btn-outline-danger';

            let icon = document.createElement('i');
            icon.className = 'fa-solid fa-trash me-2';

            let removeSpan = document.createElement('span');
            removeSpan.innerHTML = 'Remove';

            btn.addEventListener('click', function ()
            {
                enableEntrant(this.nextElementSibling.name.split('_')[3]);
                this.parentElement.remove();
            });

            btn.appendChild(icon);
            btn.appendChild(removeSpan);

            item.appendChild(btn);

            let input = document.createElement('input');
            input.type ='hidden'
            input.name ='ticket_' + ticket_id + '_entrant_' + entrant_id;
            input.value = '1';

            item.appendChild(input);

            console.log('#ticket_' + ticket_id + '_entrants');
            document.querySelector('#ticket_' + ticket_id + '_entrants').appendChild(item);
        }

        // Load entrant data
        let entrants = {!! $entrants !!};
        updateSelects(entrants);

        /**
         * Attach event listener to each entry button.
         * On click, the listener will remove the entrant from the selects' options.
         */
        document.querySelectorAll('.entry-button').forEach((btn) =>
        {
            btn.addEventListener('click', function ()
            {
                if (this.previousElementSibling.options.namedItem(this.previousElementSibling.value).disabled) {
                    // TODO: handle error
                } else {
                    disableEntrant(this.previousElementSibling.value);
                    addEntrantItem(this.previousElementSibling.id.split('_')[1], this.previousElementSibling.value,
                        this.previousElementSibling.options.namedItem(this.previousElementSibling.value).innerHTML);
                }
            });
        });
    </script>
@endsection
