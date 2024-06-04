@extends('layout.app')

@section('content')

    @include('components.event-detail-header')

    <p>
        This form <strong>does not save as you go</strong>, so please complete your checkout in one go!
    </p>

    <p>
        Required fields are marked with an asterisk (<span class="text-danger">*</span>).
    </p>

    <form method="POST" action="{{ route('event.tickets.purchase', $event) }}">
        @csrf

        @guest
            <hr>

            <div class="row pt-3 mb-4">
                <div class="col-lg-4">
                    <h3 class="h5">Your Details</h3>

                    <p>
                        We need your details so we can send you your tickets, and so the event organiser can contact
                        you.
                    </p>
                    <p>
                        Why not <a href="{{ route('register') }}">make an account</a> to keep your order history in one
                        place, and to save these details?
                    </p>
                </div>

                {{-- Buyer details --}}
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label for="buyer_email">Email address<span class="text-danger">*</span></label>
                        <input type="email"
                               name="buyer_email"
                               id="buyer_email"
                               class="form-control"
                               value="{{ old('buyer_email') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="buyer_phone">Phone number<span class="text-danger">*</span></label>
                        <input
                               type="text"
                               name="buyer_phone"
                               id="buyer_phone"
                               class="form-control"
                               value="{{ old('buyer_phone') }}"
                               required>
                    </div>
                </div>
            </div>
        @endguest

        @foreach($tickets as $ticket)
            <hr>

            <div class="row pt-3 mb-4">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <h3 class="h5">{{ $ticket->name }}</h3>

                    <h4 class="h6 card-subtitle">
                        {{ $ticket->time->format('D j M Y') }}
                        &middot;
                        £{{ number_format($ticket->price/100, 2) }}
                    </h4>

                    <p>{!! $ticket->description !!}</p>

                    <small class="text-muted">
                        To get more {{ $event->isRace() ? 'entries' : 'tickets' }} of this type, you must start
                        over, or finish and then make a new order.
                    </small>

                    <input type="hidden" name="quantity_{{ $ticket->id }}" value="{{ $ticket->quantity }}">
                </div>

                <div class="col-lg-8">
                    @for($i = 0; $i < $ticket->quantity; $i++)
                        @if($i > 0) <hr> @endif

                        <div @class(['pt-3' => $i !== 0])>
                            <h5>{{ $event->isRace() ? 'Entry' : 'Ticket' }} {{ $i + 1 }}</h5>

                            {{-- Racer search --}}
                            @if($event->isRace())
                                <div id="search_{{ $ticket->id . '_' . $i }}">
                                    <div class="mb-2">
                                        <label for="racer_search_{{ $ticket->id . '_' . $i }}">Pick a registered racer to enter</label>
                                        <input type="search"
                                               id="racer_search_{{ $ticket->id . '_' . $i }}"
                                               class="form-control"
                                               placeholder="Start typing to find racer...">
                                    </div>

                                    <div class="rounded border p-2 mb-3"
                                         style="max-height: 200px; overflow:scroll; -webkit-overflow-scrolling: touch;">
                                        <div class="list-group" id="competitor_list_{{ $ticket->id . '_' . $i }}">
                                            <span>Enter three or more characters to search.</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Locked message --}}
                                <div id="locked_{{ $ticket->id . '_' . $i }}"
                                     class="row d-none">
                                    <div class="col-md mb-3">
                                        <small>
                                            These details are populated from the GBR database and cannot be changed
                                            here. To change them, contact your HNGB.
                                        </small>
                                    </div>

                                    <div class="col-md-auto mb-3">
                                        {{-- Reset button --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                id="reset_{{ $ticket->id . '_' . $i }}">
                                            <i class="fa-solid fa-rotate-left me-2"></i>Reset
                                        </button>
                                    </div>
                                </div>
                            @endif

                            {{-- Ticket holder name --}}
                            <div class="mb-3">
                                <label for="name_{{ $ticket->id . '_' . $i }}">
                                    Full name<span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name_{{ $ticket->id }}[]"
                                       id="name_{{ $ticket->id . '_' . $i }}"
                                       class="form-control"
                                       required>
                            </div>

                            {{-- Ticket holder details --}}
                            @if($ticket->details)
                                <div class="row row-cols-1 row-cols-md-2">
                                    @foreach($ticket->details as $name => $detail)
                                        <div class="col mb-3"
                                             {{-- Tooltip --}}
                                             @if(array_key_exists('tooltip', $detail))
                                                 tabindex="0"
                                                 data-bs-toggle="tooltip"
                                                 data-bs-title="{{ $detail['tooltip'] }}"
                                             @endif>

                                            {{-- Label --}}
                                            <label for="{{ $name }}_{{ $ticket->id . '_' . $i }}">
                                                {{ $detail['label'] }}
                                                @if($detail['required'])
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>

                                            @if($detail['type'] === 'select')
                                                {{-- Select element --}}
                                                <select name="{{ $name }}_{{ $ticket->id }}[]"
                                                        id="{{ $name }}_{{ $ticket->id . '_' . $i }}"
                                                        class="form-select"
                                                        @required($detail['required'])
                                                        @readonly($detail['readonly'])>
                                                    {{-- Default option --}}
                                                    <option value disabled selected>Select...</option>

                                                    {{-- Possible options --}}
                                                    @foreach($detail['options'] as $value => $title)
                                                        <option value="{{ $value }}">{{ $title }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                {{-- Input element --}}
                                                <input type="{{ $detail['type'] }}"
                                                       name="{{ $name }}_{{ $ticket->id }}[]"
                                                       id="{{ $name }}_{{ $ticket->id . '_' . $i }}"
                                                       class="form-control"
                                                       @required($detail['required'])
                                                       @readonly($detail['readonly'])>
                                            @endif

                                            {{-- Help text --}}
                                            @if(array_key_exists('help', $detail))
                                                <small class="form-text">{{ $detail['help'] }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        @endforeach

        @if($event->id === 1)
            <hr>

            <div class="row pt-3 mb-4">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <h3 class="h5">Special Requests</h3>

                    <p>
                        Let the organiser know if you have any particular requests. They will contact you to let you know if
                        they can be fulfilled.
                    </p>
                </div>

                <div class="col-lg-8">
                    <div>
                        <label for="special_requests">
                            Seating requests
                        </label>
                        <textarea name="special_requests" id="special_requests" class="form-control" required></textarea>

                        <small class="form-text">
                            Let us know who you want to sit with.
                        </small>
                    </div>
                </div>
            </div>
        @endif

        <hr>

        <div class="col-12 col-lg-8 ms-auto py-2">
            <div class="d-flex justify-content-between align-items-baseline mb-3">
                <h4 class="mb-0">Total</h4>
                <span id="total_amount">£{{ number_format($total/100, 2) }}</span>
            </div>

            <small class="text-muted">
                When you press "Checkout" below, you will be redirected to our payment processor, Stripe, to pay. You
                have <strong>1 hour</strong> to complete your payment or your order will be cancelled and you will need
                to start over.
            </small>
        </div>

        <hr>
        <div class="d-flex justify-content-between">
            <a href="{{ route('home.event', $event) }}" class="btn btn-lg btn-outline-secondary">
                &laquo; Back
            </a>

            <button type="submit" class="btn btn-lg btn-primary">Checkout &raquo;</button>
        </div>
    </form>

@endsection

@section('scripts')
    <script>
        /**
         * Render a selectable list of competitors to display search query results.
         *
         * @param data JSON response to API call for competitor data
         * @param node The element to render the data in
         */
        function renderCompetitorList(data, node)
        {
            if (data.length === 0) {
                // Display 0 results
                node.innerHTML = `` +
                    `<span>` +
                        `No racers found. Try searching something else, or enter details manually ` +
                        `below.` +
                    `</span>`;
            } else {
                // Clear the competitor list
                node.innerHTML = '';

                // For each result
                for (let key in data) {
                    // Get competitor data
                    let competitor = data[key];

                    // Compute the ticket and node IDs
                    let id = node.id.split('_');
                    let ticket_id = id[2];
                    let node_id = id[3];

                    // Render the competitor entry
                    node.insertAdjacentHTML('beforeend', `` +
                        `<button type="button" id="entry_${node_id}_competitor_${competitor.REGNO}" ` +
                           `class="list-group-item list-group-item-action">` +
                            `<div class="d-md-flex w-100 justify-content-between">` +
                                `<p class="h6 mb-1">${competitor.FIRSTNAME} ${competitor.LASTNAME} &middot;
                                    ${competitor.REGNO}</p>` +
                                `<small>${competitor.GENDER === "M" ? `Male` : `Female`} &middot
                                    ${competitor.YOB} &middot; <em>UK Club:</em> ${competitor.CLUB_UK}</small>` +
                            `</div>` +
                        `</button>`
                    );

                    // Add event listener for populating entry data
                    node.lastElementChild.addEventListener('click', function()
                    {
                        // Remove all active classes to deselect all rows, then mark the current one as active
                        this.parentElement.childNodes.forEach(function(self) { self.classList.remove('active'); });
                        this.classList.add('active');

                        autofillCompetitor(
                            ticket_id,
                            node_id,
                            competitor.FIRSTNAME,
                            competitor.LASTNAME,
                            competitor.REGNO,
                            competitor.GENDER,
                            competitor.YOB,
                            competitor.CLUB_UK);
                        lockCompetitorFields(ticket_id, node_id);
                    });
                }
            }
        }

        /**
         * 'Lock' (make readonly) the form fields for the competitor when a competitor search result is selected.
         */
        function lockCompetitorFields(ticket, node)
        {
            let id = ticket + '_' + node;

            // Hide the racer search
            let search = document.querySelector(`#racer_search_${id}`);
            search.classList.add('d-none');
            search.parentElement.classList.add('d-none');
            search.parentElement.nextElementSibling.classList.add('d-none');

            // Show the locked message
            document.querySelector(`#locked_${id}`).classList.remove('d-none');

            // Lock relevant competitor fields
            document.querySelectorAll(`[id*="${id}"]`).forEach(function(n)
            {
                switch (n.tagName.toLowerCase()) {
                    case 'input':
                        // Make inputs readonly
                        n.readOnly = true;
                        break;
                    case 'select':
                        // Disable all but selected option for selects
                        let options = n.options;

                        for (let option of options) {
                            if (option.value !== options[n.selectedIndex].value) option.disabled = true;
                        }
                }
            })
        }

        /**
         * Autofill the selected competitior's details into the entry form.
         *
         * @param ticket_id ID of the ticket type that the entry is for
         * @param entryno   Sequential number within the ticket types that the entry is for
         * @param firstname Competitor's first name
         * @param lastname  Competitor's last name
         * @param regno     Competitor's GBR registration number
         * @param gender    Competitor's gender (M/F only)
         * @param yob       Competitor's year of birth
         * @param club      Competitor's UK club (abbreviation)
         */
        function autofillCompetitor(ticket_id, entryno, firstname, lastname, regno, gender, yob, club)
        {
            let id = ticket_id + '_' + entryno;

            document.querySelector(`#name_${id}`).value = firstname + ' ' + lastname;
            document.querySelector(`#yob_${id}`).value = yob;
            document.querySelector(`#club_${id}`).value = club ?? '';

            if (regno > 0) document.querySelector(`#gbr_no_${id}`).value = regno;

            if (gender === 'M') document.querySelector(`#gender_${id}`).selectedIndex = 1;
            else document.querySelector(`#gender_${id}`).selectedIndex = 2;
        }

        /*
         * Attach an event listener to all search fields to implement live competitor search.
         */
        document.querySelectorAll(`[id^="racer_search_"]`).forEach(function(search)
        {
            // Find corresponding competitor list box to populate
            let id = search.id.split('_');
            let list = document.querySelector(`#competitor_list_${id[2]}_${id[3]}`);

            search.addEventListener('keyup', function()
            {
                if (this.value.length < 3) {
                    list.innerHTML = '<span>Enter three or more characters to search.</span>';
                } else {
                    list.innerHTML = '' +
                        '<div class="d-flex align-items-center">' +
                            '<strong role="status">Loading...</strong>' +
                            '<div class="spinner-border ms-auto" aria-hidden="true"></div>' +
                        '</div>';

                    fetch(`{{ config('app.url') }}/api/active-registrations/${this.value}`)
                        .then((response) => response.json())
                        .then(function(data) { renderCompetitorList(data, list); });
                }
            });
        });

        /*
         * Attach an event listener to all reset buttons to implement unlocking of competitor fields
         */
        document.querySelectorAll('[id^="reset_"]').forEach(function(reset)
        {
            // Build ID of entry from reset button ID
            let id = reset.id.split('_');
            id = id[1] + '_' + id[2];

            reset.addEventListener('click', function()
            {
                // Clear and enable relevant form fields
                document.querySelectorAll(`[id*="${id}"]`).forEach(function(n)
                {
                    if (['input'].includes(n.tagName.toLowerCase())) {
                        n.value = '';
                        n.readOnly = false;
                    }
                });

                // Show the racer search
                let search = document.querySelector(`#racer_search_${id}`);
                search.classList.remove('d-none');
                search.parentElement.classList.remove('d-none');
                search.parentElement.nextElementSibling.classList.remove('d-none');

                // Hide the locked message
                document.querySelector(`#locked_${id}`).classList.add('d-none');
            });
        });
    </script>
@endsection
