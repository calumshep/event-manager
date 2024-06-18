@extends('layout.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        <h1>@yield('title')</h1>

        <a class="btn btn-outline-secondary" href="{{ route('events.index') }}">&laquo; Back to My Events</a>
    </div>

    @include('components.status')

    <hr>

    <div class="row">
        @unless(Route::is('events.create'))
            <div class="col-md-auto border-end-md">
                @yield('sidebar')
            </div>
        @endunless

        <div class="col-md">
            @yield('form')
        </div>
    </div>

    @yield('modals')

@endsection
