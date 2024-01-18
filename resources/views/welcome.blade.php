@extends('layout.app')

@section('content')

    <div class="text-center py-4">
        <h1>{{ config('app.name') }}</h1>

        <p class="lead mb-4">
            Events and tickets, done right...
        </p>

        <!--
        <div class="input-group mb-3 w-75 mx-auto">
            <div class="form-floating">
                <input type="search" class="form-control" name="search" id="search" placeholder="Alpine champs...">
                <label for="search">Search events...</label>
            </div>

            <button type="button" class="btn btn-outline-primary">
                <i class="fa-solid fa-magnifying-glass fa-xl mx-1"></i>
                <span class="d-none">Search</span>
            </button>
        </div>
        -->
    </div>

    <hr>

    <!--<div class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Recommended For You</h2>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card h-100 shadow">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                             xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                             preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#55595c"></rect>
                            <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                        </svg>
                        <div class="card-body">
                            <h2 class="card-title h3">Event Title</h2>
                            <h3 class="card-subtitle fs-5 text-muted">Event Date</h3>
                            <p class="card-text">
                                Event Location
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

    <h2 class="h3 mb-3">Upcoming Events</h2>

    @forelse($events as $event)
        <div class="d-flex align-items-start rounded shadow p-3 mb-3">
            <img src="https://placehold.co/50" class="rounded" alt="placeholder">

            <div class="ms-3 flex-fill">
                <h5>
                    <a class="text-decoration-none" href="{{ route('home.event', $event) }}">
                        {{ $event->name }} &middot;
                        <span class="text-muted fs-6">
                        {{ $event->start->format('D j M Y') }} {{ isset($event->end) ? 'to ' . $event->end->format('D j M Y') : null }}
                    </span>
                    </a>
                </h5>

                <p>
                    {{ $event->short_desc }}
                </p>

                <div class="d-flex align-items-baseline justify-content-between">
                    <div>
                        <a href="#">GBSki</a> &middot;
                        <a href="#"><i class="fa-brands fa-square-facebook fa-fw"></i></a>
                    </div>

                    <a href="{{ route('home.event', $event) }}" class="btn btn-primary">
                        Enter &raquo;
                    </a>
                </div>
            </div>
        </div>
    @empty
        <p class="lead">
            There are no upcoming events.
        </p>
    @endforelse

    {{ $events->links() }}

    <hr class="my-5">

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
    </div>

@endsection
