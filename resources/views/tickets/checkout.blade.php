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
                        To get more tickets of this type, you must start over, or finish and then make a new order.
                    </small>

                    <input type="hidden" name="quantity_{{ $ticket->id }}" value="{{ $ticket->quantity }}">
                </div>

                <div class="col-lg-8">
                    @for($i = 0; $i < $ticket->quantity; $i++)
                        <div @class(['pt-3' => $i !== 0])>
                            <h5>Ticket {{ $i + 1 }}</h5>

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

                                            <input type="{{ $detail['type'] }}"
                                                   name="{{ $name }}_{{ $ticket->id }}[]"
                                                   id="{{ $name }}_{{ $ticket->id . '_' . $i }}"
                                                   class="form-control"
                                                @required($detail['required'])>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        @endforeach

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
