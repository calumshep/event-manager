@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        <h1>Your Organisations</h1>

        <a href="{{ route('organisations.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>New organisation
        </a>
    </div>

    @include('components.status')

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-3">
        @forelse($organisations as $organisation)
            <div class="col">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h2 class="h4">{{ $organisation->name }}</h2>

                        <p><strong>
                            <a href="{{ $organisation->website }}">{{ $organisation->website }}</a>
                        </strong></p>

                        <p>{{ $organisation->description }}</p>

                        <div class="text-end">
                            <a class="btn btn-primary" href="{{ route('organisations.show', $organisation) }}">
                                View &raquo;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>
                No organisations found. Perhaps you need to
                <a href="{{ route('organisations.create') }}">create one</a>?
            </p>
        @endforelse
    </div>

@endsection
