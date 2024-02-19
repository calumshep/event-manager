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
        @forelse($organisations as $organisation)
            <div class="col">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h2 class="h4">{{ $organisation->name }}</h2>

                        <p><strong>
                                <a href="{{ $organisation->website }}">{{ $organisation->website }}</a>
                        </strong></p>

                        <div class="text-end">
                            <a class="btn btn-primary" href="{{ route('organisations.show', $organisation) }}">
                                View &raquo;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="lead">No organisations found</p>
        @endforelse
    </div>

    {{ $organisations->links() }}

@endsection
