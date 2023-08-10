@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        <h1>Your Competitors</h1>

        <a href="{{ route('competitors.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>New competitor
        </a>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-3">
        @forelse($competitors as $competitor)
            <div class="col">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h2 class="h4">{{ $competitor->name }}</h2>

                        <dl class="card-text">
                            <dt>Date of birth</dt>
                            <dd>{{ $competitor->dob->format('d/m/Y') }}</dd>

                            <dt>Gender</dt>
                            <dd>{{ ucfirst($competitor->gender) }}</dd>

                            @if($competitor->reg_id)
                                <dt>BASS number</dt>
                                <dd>{{ $competitor->reg_id }}</dd>
                            @endif
                        </dl>

                        <div class="text-end">
                            <a class="btn btn-primary" href="{{ route('competitors.show', $competitor) }}">
                                View &raquo;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>
                No competitors found. Perhaps you need to
                <a href="{{ route('competitors.create') }}">create one</a>?
            </p>
        @endforelse
    </div>

@endsection
