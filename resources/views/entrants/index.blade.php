@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        <h1>Your Entrants</h1>

        <a href="{{ route('entrants.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>New entrant
        </a>
    </div>

    @include('components.status')

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-3">
        @forelse($entrants as $entrant)
            <div class="col">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h2 class="h4">{{ $entrant->name }}</h2>

                        <dl class="card-text">
                            <dt>Date of birth</dt>
                            <dd>{{ $entrant->dob->format('d/m/Y') }}</dd>

                            <dt>Gender</dt>
                            <dd>{{ ucfirst($entrant->gender) }}</dd>

                            @if($entrant->reg_id)
                                <dt>BASS number</dt>
                                <dd>{{ $entrant->reg_id }}</dd>
                            @endif
                        </dl>

                        <div class="text-end">
                            <a class="btn btn-primary" href="{{ route('entrants.show', $entrant) }}">
                                View &raquo;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>
                No entrants found. Perhaps you need to
                <a href="{{ route('entrants.create') }}">create one</a>?
            </p>
        @endforelse
    </div>

@endsection
