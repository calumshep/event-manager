@extends('layout.app')

@section('content')

    <h1>@yield('title')</h1>

    @include('components.status')

    <hr>

    <div class="row">
        <div class="col-md-auto border-end-md">
            @yield('sidebar')
        </div>

        <div class="col-md">
            @yield('form')
        </div>
    </div>

    @yield('modals')

@endsection
