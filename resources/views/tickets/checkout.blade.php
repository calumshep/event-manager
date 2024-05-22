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
                                    <label for="racer_search">Pick a registered racer to enter</label>
                                    <input type="search"
                                           id="racer_search"
                                           class="form-control"
                                           placeholder="Start typing to find racer...">
                                </div>

                                <div class="rounded border p-2 mb-3"
                                     style="max-height: 200px; overflow:scroll; -webkit-overflow-scrolling: touch;">
                                    <div class="list-group" id="competitorList">
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
        const list = document.getElementById('competitorList');

        function updateDetails(data)
        {
            // TODO
        }

        function updateCompetitorList(data)
        {
            list.innerHTML = '';

            if (data.length === 0) {
                list.innerHTML = '' +
                    '<span>' +
                        'No racers found. Try searching something else, or enter details manually ' +
                        'below.' +
                    '</span>';
            } else {
                for (const dataKey in data) {
                    let competitor = data[dataKey];

                    list.insertAdjacentHTML('beforeend', '' +
                        '<a href="#" class="list-group-item list-group-item-action">' +
                            '<div class="d-flex w-100 justify-content-between">' +
                                `<p class="h6 mb-1">${competitor.FIRSTNAME} ${competitor.LASTNAME} &middot;
                                    ${competitor.REGNO}</p>` +
                                `<small>${competitor.GENDER === "M" ? 'Male' : 'Female'}</small>` +
                            '</div>' +
                        '</a>'
                    );
                }
            }
        }

        document.getElementById('racer_search').addEventListener('keyup', function () {
            if (this.value.length < 3) {
                list.innerHTML = '<span>Enter three or more characters to search.</span>';
            } else {
                list.innerHTML = '' +
                    '<div class="d-flex align-items-center">' +
                        '<strong role="status">Loading...</strong>' +
                        '<div class="spinner-border ms-auto" aria-hidden="true"></div>' +
                    '</div>';

                let url = '{{ config('app.url') }}/api/active-registrations/' + this.value;
                fetch(url)
                    .then((res) => res.json())
                    .then(updateCompetitorList);
            }
        });
    </script>
@endsection
