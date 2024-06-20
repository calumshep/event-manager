@extends('layout.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        <h1>Your Organisations</h1>

        <a href="{{ route('organisations.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>New organisation
        </a>
    </div>

    <p>
        Each event you run is assigned to one of your organisations. This makes it clear to your attendees who is
        running the event, who is responsible for it, and tells us who to pay.
    </p>
    <p>
        In future, you will be able to add others to your organisations, and share admin access to events within those
        organisations.
    </p>

    <hr>

    @include('components.status')

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 mb-3">
        @forelse($organisations as $org)
            <div class="col">
                <div class="card h-100 shadow">
                    <div class="card-body" style="transform: rotate(0);">
                        @if(\App\Support\StripeHelper::accountRequirements($org))
                            <span class="badge text-bg-warning float-end">
                                Onboarding incomplete
                            </span>
                        @endif
                        <h2 class="h3 card-title">
                            <a href="{{ route('organisations.show', $org) }}" class="stretched-link text-decoration-none">
                                {{ $org->name }}
                            </a>
                        </h2>

                        <p class="card-text text-truncate">{{ strip_tags($org->description) }}</p>
                    </div>

                    <ul class="list-group list-group-flush">
                        <a href="{{ route('organisations.show', $org) }}"
                           class="list-group-item list-group-item-action list-group-item-primary">
                            <i class="fa-solid fa-circle-info fa-fw me-2"></i>
                            Details
                        </a>

                        <a href="{{ route('organisations.team.index', $org) }}"
                           class="list-group-item list-group-item-action list-group-item-success">
                            <i class="fa-solid fa-users-rectangle fa-fw me-2"></i>
                            Team Members
                        </a>
                    </ul>
                </div>
            </div>
        @empty
            <p class="lead">
                No organisations found. Maybe you want to <a href="{{ route('organisations.create') }}">create one</a>?
            </p>
        @endforelse
    </div>

    {{ $organisations->links() }}

@endsection
