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
                                <div class="rounded border p-2 mb-3"
                                     style="max-height: 200px; overflow:scroll; -webkit-overflow-scrolling: touch;"
                                     id="search_{{ $ticket->id . '_' . $i }}">
                                    <div class="form-floating mb-2">
                                        <input type="search"
                                               id="racer_search_{{ $ticket->id . '_' . $i }}"
                                               class="form-control"
                                               placeholder="Search for a registered racer">
                                        <label for="racer_search_{{ $ticket->id . '_' . $i }}">
                                            Search for a registered racer
                                        </label>
                                    </div>

                                    <div class="list-group" id="competitor_list_{{ $ticket->id . '_' . $i }}">
                                        <span>Enter three or more characters above to search.</span>
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
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label for="firstname_{{ $ticket->id . '_' . $i }}">
                                        First name<span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="firstname_{{ $ticket->id }}[]"
                                           id="firstname_{{ $ticket->id . '_' . $i }}"
                                           class="form-control"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="lastname_{{ $ticket->id . '_' . $i }}">
                                        Last name<span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           name="lastname_{{ $ticket->id }}[]"
                                           id="lastname_{{ $ticket->id . '_' . $i }}"
                                           class="form-control"
                                           required>
                                </div>
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
                                                @if($name === 'yob')
                                                    <div class="input-group">
                                                        <input type="{{ $detail['type'] }}"
                                                               name="{{ $name }}_{{ $ticket->id }}[]"
                                                               id="{{ $name }}_{{ $ticket->id . '_' . $i }}"
                                                               class="form-control"
                                                               max="{{ $detail['max'] }}"
                                                               @required($detail['required'])
                                                               @readonly($detail['readonly'])>
                                                        <span id="agecat_{{ $ticket->id . '_' . $i }}"
                                                              class="input-group-text">Category</span>
                                                    </div>
                                                @else
                                                    <input type="{{ $detail['type'] }}"
                                                           name="{{ $name }}_{{ $ticket->id }}[]"
                                                           id="{{ $name }}_{{ $ticket->id . '_' . $i }}"
                                                           class="form-control"
                                                           @required($detail['required'])
                                                           @readonly($detail['readonly'])>
                                                @endif
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
    @if($event->isRace())
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
            document.querySelector(`#racer_search_${id}`).parentElement.parentElement.classList.add('d-none');

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

            document.querySelector(`#firstname_${id}`).value    = firstname;
            document.querySelector(`#lastname_${id}`).value     = lastname;
            document.querySelector(`#club_${id}`).value         = club ?? '';

            let yob_input = document.querySelector(`#yob_${id}`)
            yob_input.value = yob;
            renderAgeCat(yob_input);

            if (regno > 0) document.querySelector(`#gbr_no_${id}`).value = regno;

            if (gender === 'M') document.querySelector(`#gender_${id}`).selectedIndex = 1;
            else document.querySelector(`#gender_${id}`).selectedIndex = 2;
        }

        /**
         * Age categories, ordered from youngest to oldest
         */
        const CATEGORIES = ["U8", "U10", "U12", "U14", "U16", "U18", "U21", "SEN", "MAS"];
        const  U8_AGES   = [ 6,  7];
        const U10_AGES   = [ 8,  9];
        const U12_AGES   = [10, 11];
        const U14_AGES   = [12, 13];
        const U16_AGES   = [14, 15];
        const U18_AGES   = [16, 17];
        const U21_AGES   = [18, 19, 20];
        const SEN_AGES   = [21, 22, 23, 24, 25, 26, 27, 28, 29];

        /**
         * Get the category-adjusted years of birth corresponding to the given array of ages.
         * Will return years of birth which would put trainees in new-season categories if the current system date is 1st
         * May or later.
         *
         * @param ages
         * @returns {*[]}
         */
        function years(ages)
        {
            let years = [];

            for (let age of ages) {
                let now = new Date();
                if (now.getTime() > new Date(now.getFullYear(), 5, 1)) {
                    // Categories have switched over, calculate using higher age bands
                    years.push(now.getFullYear() - age);
                } else {
                    // Categories are still on old season, calculate using lower bands
                    years.push(now.getFullYear() - age - 1);
                }
            }

            return years;
        }

        /**
         * Get the category corresponding to the given year of birth
         *
         * @param year
         * @returns {string}
         */
        function category(year)
        {
            if        (years(U8_AGES).includes(Number(year)))  {
                return CATEGORIES[0];
            } else if (years(U10_AGES).includes(Number(year))) {
                return CATEGORIES[1];
            } else if (years(U12_AGES).includes(Number(year))) {
                return CATEGORIES[2];
            } else if (years(U14_AGES).includes(Number(year))) {
                return CATEGORIES[3];
            } else if (years(U16_AGES).includes(Number(year))) {
                return CATEGORIES[4];
            } else if (years(U18_AGES).includes(Number(year))) {
                return CATEGORIES[5];
            } else if (years(U21_AGES).includes(Number(year))) {
                return CATEGORIES[6];
            } else if (years(SEN_AGES).includes(Number(year))) {
                return CATEGORIES[7];
            } else {
                return CATEGORIES[8];
            }
        }

        /**
         * Get years of birth corresponding to U8 category
         * @returns {*[]} array of years
         */
        function u8()
        {
            return years(U8_AGES);
        }

        /**
         * Get years of birth corresponding to U10 category
         * @returns {*[]} array of years
         */
        function u10()
        {
            return years(U10_AGES);
        }

        /**
         * Get years of birth corresponding to U12 category
         * @returns {*[]} array of years
         */
        function u12()
        {
            return years(U12_AGES);
        }

        /**
         * Get years of birth corresponding to U14 category
         * @returns {*[]} array of years
         */
        function u14()
        {
            return years(U14_AGES);
        }

        /**
         * Get years of birth corresponding to U16 category
         * @returns {*[]} array of years
         */
        function u16()
        {
            return years(U16_AGES);
        }

        /**
         * Get years of birth corresponding to U18 category
         * @returns {*[]} array of years
         */
        function u18()
        {
            return years(U18_AGES);
        }

        /**
         * Get years of birth corresponding to U21 category
         * @returns {*[]} array of years
         */
        function u21()
        {
            return years(U21_AGES);
        }

        /**
         * Get years of birth corresponding to SEN category
         * @returns {*[]} array of years
         */
        function sen()
        {
            return years(SEN_AGES);
        }

        /**
         * Get years of birth corresponding to MAS category
         * @returns {*[]} array of years
         */
        function mas()
        {
            return years(SEN_AGES);
        }

        function renderAgeCat(yob_input)
        {
            // Build ID of entry from reset button ID
            let id = yob_input.id.split('_');
            id = id[1] + '_' + id[2];

            // Find the corresponding age category span element
            document.querySelector(`#agecat_${id}`).innerHTML =
                (yob_input.value.length > 3)
                    // If valid YOB, find and output category
                    ? category(yob_input.value)
                    // Otherwise leave placeholder
                    : 'Category';
        }

        /*
         * Attach an event listener to fetch and display category for each YOB field.
         */
        document.querySelectorAll('[id^="yob"]').forEach(function(node)
        {
            // Add event listener on keyup *and* change to account for non-keyboard changes to input
            ['change', 'keyup'].forEach(function(event)
            {
                node.addEventListener(event, function()
                {
                    renderAgeCat(node);
                });
            });
        });

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
                    list.innerHTML = '<span>Enter three or more characters above to search.</span>';
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
                document.querySelector(`#racer_search_${id}`).parentElement.parentElement.classList.remove('d-none');

                // Hide the locked message
                document.querySelector(`#locked_${id}`).classList.add('d-none');
            });
        });
    </script>
    @endif
@endsection
