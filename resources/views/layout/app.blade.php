<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Event Manager') }}</title>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    @yield('head')
</head>

<body>
@include('layout.navigation')

<!-- Page Content -->
<div class="@unless(Route::is('home')) container-xxl @endunless mb-4">
    @if(Auth::check() && !Route::is('home'))
        <div class="row gx-4">
            <div class="col-lg-3 border-end-lg d-none d-lg-block">
                @include('layout.sidebar')
            </div>
            <main class="col-lg-9">
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endauth
</div>

@include('layout.footer')

@yield('scripts')
<script src="https://kit.fontawesome.com/c000864a8c.js" crossorigin="anonymous"></script>
</body>
</html>
