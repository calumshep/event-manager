@extends('layouts.app')

@section('content')

<h1 class="mb-3">Upcoming Events</h1>

@forelse($events as $event)
    <div class="d-flex align-items-start rounded shadow p-3 mb-3">
        <img src="https://placehold.co/50" class="rounded" alt="placeholder">

        <div class="ms-3">
            <h5>
                <a class="text-decoration-none" href="#">
                    {{ $event->name }} &middot;
                    <span class="text-muted fs-6">
                        {{ $event->start->format('D j M Y') }}
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

                <a href="{{ route('events.show', $event) }}" class="btn btn-primary">
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

@endsection
