@extends('layout.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        <h1>@yield('title')</h1>

        @if(Route::is('events*'))
            <a class="btn btn-outline-secondary" href="{{ route('events.index') }}">&laquo; Back to My Events</a>
        @elseif(Route::is('organisations*'))
            <a class="btn btn-outline-secondary" href="{{ route('organisations.index') }}">&laquo; Back to My Organisations</a>
        @endif
    </div>

    @include('components.status')

    <hr>

    <div class="row">
        @unless(Route::is('events.create') || Route::is('organisations.create'))
            <div class="col-md-auto border-end-md">
                @yield('sidebar')
                <hr class="d-md-none">
            </div>
        @endunless

        <div class="col-md">
            @yield('form')
        </div>
    </div>

    @yield('modals')

@endsection
