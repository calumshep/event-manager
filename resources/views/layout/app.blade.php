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
    <main class="container">
        @yield('content')
    </main>

    @include('layout.footer')

    @yield('scripts')
    <script src="https://kit.fontawesome.com/c000864a8c.js" crossorigin="anonymous"></script>
</body>
</html>
