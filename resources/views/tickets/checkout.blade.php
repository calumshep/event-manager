@extends('layout.app')

@section('content')

    @include('components.event-detail-header')

    <p>
        This form <strong>does not save as you go</strong>, so please complete your checkout in one go!
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

                            @if($event->isRace())
                                <div class="mb-2">
                                    <label for="racer_search_{{ $i }}">Pick a registered racer to enter</label>
                                    <input type="search"
                                           id="racer_search_{{ $i }}"
                                           class="form-control"
                                           placeholder="Start typing to find racer...">
                                </div>

                                <div class="rounded border p-2 mb-3"
                                     style="max-height: 200px; overflow:scroll; -webkit-overflow-scrolling: touch;">
                                    <div class="list-group" id="competitor_list_{{ $i }}">
                                        <span>Enter three or more characters to search.</span>
                                    </div>
                                </div>
                            @endif

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

                            @if($ticket->details)
                                <div class="row row-cols-1 row-cols-md-2">
                                    @foreach($ticket->details as $name => $detail)
                                        <div class="col mb-3">
                                            <label for="{{ $name }}_{{ $ticket->id . '_' . $i }}">
                                                {{ $detail['label'] }}
                                                @if($detail['required'])
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>

                                            @if($detail['type'] === 'select')
                                                <select name="{{ $name }}_{{ $ticket->id }}[]"
                                                        id="{{ $name }}_{{ $ticket->id . '_' . $i }}"
                                                        class="form-select"
                                                        @required($detail['required'])>
                                                        <option value disabled selected>Select...</option>
                                                    @foreach($detail['options'] as $value => $title)
                                                        <option value="{{ $value }}">{{ $title }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="{{ $detail['type'] }}"
                                                       name="{{ $name }}_{{ $ticket->id }}[]"
                                                       id="{{ $name }}_{{ $ticket->id . '_' . $i }}"
                                                       class="form-control"
                                                       @required($detail['required'])>
                                            @endif
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
            // Clear the competitor list
            node.innerHTML = '';

            if (data.length === 0) {
                // Display 0 results
                node.innerHTML = '' +
                    '<span>' +
                        'No racers found. Try searching something else, or enter details manually ' +
                        'below.' +
                    '</span>';
            } else {
                // For each result
                for (let key in data) {
                    // Get competitor data
                    let competitor = data[key];
                    let node_id = node.id.split('_')[2];

                    // Render the competitor entry
                    node.insertAdjacentHTML('beforeend', '' +
                        `<button type="button" id="entry_${node_id}_competitor_${competitor.REGNO}" ` +
                           'class="list-group-item list-group-item-action">' +
                            '<div class="d-md-flex w-100 justify-content-between">' +
                                `<p class="h6 mb-1">${competitor.FIRSTNAME} ${competitor.LASTNAME} &middot;
                                    ${competitor.REGNO}</p>` +
                                `<small>${competitor.GENDER === "M" ? 'Male' : 'Female'} &middot
                                    ${competitor.YOB} &middot; <em>UK Club:</em> ${competitor.CLUB_UK}</small>` +
                            '</div>' +
                        '</button>'
                    );

                    // Add event listener for populating entry data
                    node.lastElementChild.addEventListener('click', function() {
                        this.parentElement.childNodes.forEach(function(self) {
                            self.classList.remove('active');
                        })

                        this.classList.add('active');
                        autofillCompetitor(
                            // hack-y way to get ticket type ID...lol
                            this.parentElement.parentElement.parentElement.childNodes[7].childNodes[3].id.split('_')[1],
                            // sequential order of this entry within the ticket type
                            node_id,
                            competitor.FIRSTNAME,
                            competitor.LASTNAME,
                            competitor.REGNO,
                            competitor.GENDER,
                            competitor.YOB,
                            competitor.CLUB_UK);
                    });
                }
            }
        }

        /*
         * Attach an event listener to all search fields to implement live competitor search
         */
        document.querySelectorAll('[id^="racer_search_"]').forEach(function (search) {
            // Find corresponding competitor list box to populate
            let list = document.querySelector('#competitor_list_' + search.id.split('_')[2]);

            search.addEventListener('keyup', function ()
            {
                if (this.value.length < 3) {
                    list.innerHTML = '<span>Enter three or more characters to search.</span>';
                } else {
                    list.innerHTML = '' +
                        '<div class="d-flex align-items-center">' +
                            '<strong role="status">Loading...</strong>' +
                            '<div class="spinner-border ms-auto" aria-hidden="true"></div>' +
                        '</div>';

                    fetch('{{ config('app.url') }}/api/active-registrations/' + this.value)
                        .then((response) => response.json())
                        .then(function(data) {
                            renderCompetitorList(data, list);
                    });
                }
            });
        });

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
            document.getElementById('name_' + ticket_id + '_' + entryno).value = firstname + ' ' + lastname;
            if (regno > 0) {
                document.getElementById('gbr_no_' + ticket_id + '_' + entryno).value = regno;
            }
            if (gender === 'M') {
                document.getElementById('gender_' + ticket_id + '_' + entryno).selectedIndex = 1;
            } else {
                document.getElementById('gender_' + ticket_id + '_' + entryno).selectedIndex = 2;
            }
            document.getElementById('yob_' + ticket_id + '_' + entryno).value = yob;
            document.getElementById('club_' + ticket_id + '_' + entryno).value = club ?? '';
        }
    </script>
@endsection
