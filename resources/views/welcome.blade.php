@extends('layout.app')

@section('content')

    <div class="text-center py-4">
        <h1>{{ config('app.name') }}</h1>

        {{--<div class="input-group mb-3 w-75 mx-auto">
            <div class="form-floating">
                <input type="search" class="form-control" name="search" id="search" placeholder="Alpine champs...">
                <label for="search">Search events...</label>
            </div>

            <button type="button" class="btn btn-outline-primary">
                <i class="fa-solid fa-magnifying-glass fa-xl mx-1"></i>
                <span class="d-none">Search</span>
            </button>
        </div>--}}
    </div>

    <hr>

    <h2 class="h3 mb-3">Upcoming Events</h2>

    @forelse($events as $event)
    {{-- <img src="https://placehold.co/50" class="rounded" alt="placeholder">--> --}}

        <div class="card shadow mb-3" style="transform: rotate(0);">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <h5>
                            {{ $event->name }} &middot;
                            <span class="text-muted fs-6">
                        {{ $event->start->format('D j M Y') }} {{ isset($event->end) ? 'to ' . $event->end->format('D j M Y') : null }}
                    </span>
                        </h5>

                        <p class="card-text">{{ $event->short_desc }}</p>
                    </div>

                    <div class="col-md-auto mt-md-0 mt-3">
                        <a href="{{ route('home.event', $event) }}" class="btn btn-primary stretched-link card-text">
                            View &raquo;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="lead">
            There are no upcoming events.
        </p>
    @endforelse

    {{ $events->links() }}

    {{--<hr class="my-5">

    <div class="row align-items-center mb-5">
        <div class="col-md-4">
            <h2>How do I get tickets?</h2>
            <p>
                Firstly, you (the bill-payer) will need an account to log in. You can create one for free in seconds.
            </p>
            <p>
                Then you just need to find the event you want to attend, and pay for your tickets. It's that easy!
            </p>
        </div>

        <div class="col-md-8">
            <div class="p-5 rounded shadow text-center">
                @auth
                    <h3 class="card-title">Hi, {{ auth()->user()->first_name }}!</h3>
                    <p class="lead">
                        You are signed in. What are you waiting for?
                    </p>
                @else
                    <h3 class="card-title">Create Your Account</h3>
                    <p class="lead">
                        Click the button below to sign up. Creating an account is free!
                    </p>
                    <a class="card-text btn btn-lg btn-primary" href="{{ route('register') }}">
                        <i class="fa-solid fa-pencil me-2"></i>Sign Up
                    </a>
                    <a class="card-text btn btn-lg btn-secondary" href="#">
                        <i class="fa-solid fa-envelope me-2"></i>Contact Us
                    </a>
                @endauth
            </div>
        </div>
    </div>--}}

@endsection
