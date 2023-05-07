<nav class="navbar navbar-expand-lg navbar-dark bg-dark @unless(Route::is('home')) mb-3 @endunless">
    <div class="container-xl">
        <a href="{{ route('home') }}" class="navbar-brand">{{ config('app.name') }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link @if(Route::is('home')) active @endif" href="{{ route('home') }}">
                        <i class="fa-solid fa-house me-2"></i>
                        Home
                    </a>
                </li>

                <div class="d-lg-none">
                    @include('layouts.partials.nav-standard')

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-shield-halved fa-fw me-2"></i>
                            Admin
                        </a>
                        <ul class="dropdown-menu">
                            @include('layouts.partials.nav-admin')
                        </ul>
                    </li>
                </div>
            </ul>

            @auth
                <ul class="navbar-nav ms-auto">
                    <!-- TODO: fix colour scheme properly before enabling
                        @unless(Route::is('home'))
                        <li class="nav-item dropdown">
                            <button class="btn btn-link nav-link dropdown-toggle d-flex align-items-center"
                                    id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                                    data-bs-display="static">
                                <i class="fa-solid fa-circle-half-stroke mx-2 my-1 theme-icon-active"></i>
                                <span class="d-lg-none ms-2">Toggle theme</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme"
                                style="--bs-dropdown-min-width: 8rem;">
                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center"
                                            data-bs-theme-value="light">
                                        <i class="fa-solid fa-fw fa-sun me-2 theme-icon"></i>
                                        Light
                                        <i class="fa-solid fa-check ms-auto d-none"></i>
                                    </button>
                                </li>

                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center"
                                            data-bs-theme-value="dark">
                                        <i class="fa-solid fa-fw fa-moon me-2 theme-icon"></i>
                                        Dark
                                        <i class="fa-solid fa-check ms-auto d-none"></i>
                                    </button>
                                </li>

                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center active"
                                            data-bs-theme-value="auto">
                                        <i class="fa-solid fa-fw fa-circle-half-stroke me-2 theme-icon"></i>
                                        Auto (match system)
                                        <i class="fa-solid fa-check ms-auto d-none"></i>
                                    </button>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto">
                            <div class="vr d-none d-lg-flex h-100 mx-lg-2 text-white"></div>
                            <hr class="d-lg-none my-2 text-white-50">
                        </li>
                    @endunless
                    -->

                    <li class="nav-item me-2">
                        <a class="nav-link" href="#">
                            <i class="fa-solid fa-user-lock me-2"></i>
                            Logged in as {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </a>
                    </li>

                    <form class="d-flex" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light" type="submit">
                            <i class="fa-solid fa-key me-2"></i>
                            Sign Out
                        </button>
                    </form>

                </ul>
            @else
                <div class="me-auto"></div>
                <div class="text-end">
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">
                        <i class="fa-solid fa-key me-2"></i>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light">
                        <i class="fa-solid fa-pencil me-2"></i>
                        Sign Up
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
