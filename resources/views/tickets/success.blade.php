@extends('layout.app')

@section('content')

    <div class="text-center">
        <h1 class="h3">Purchase Confirmation</h1>

        <p>
            Your ticket purchase is complete - thank you!
        </p>
        <p>
            Your tickets will be emailed to you. You will also receipt a receipt from Stripe.
        </p>

        <dl>
            <dt>Total paid</dt>
            <dd>£{{ number_format($checkout->amount_total/100, 2) }}</dd>

            <dt>Time</dt>
            <dd>{{ \Carbon\Carbon::createFromTimestamp($checkout->created, 'Europe/London')->toDateTimeString() }}</dd>

            <dt>Payment</dt>
            <dd>{{ $checkout->payment_intent }}</dd>
        </dl>

        <a href="{{ route('home') }}" class="btn btn-primary">
            Back to Homepage
        </a>
    </div>

@endsection
