<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav me-auto mb-0">
                <li class="nav-item">
                    <a @class([
                           'nav-link',
                           'active' => Route::is('home', 'event.*'),
                       ])
                       {{ Route::is('home') ? 'aria-current="page"' : '' }}
                       href="{{ route('home') }}">
                        <i class="fa-solid fa-home fa-fw me-1"></i>
                        Home
                    </a>
                </li>

                @can('manage own events')
                    <li class="nav-item">
                        <a @class([
                               'nav-link',
                               'active' => Route::is(['events*', 'organisations*']),
                           ])
                           {{ Route::is('events*') ? 'aria-current="page"' : '' }}
                           href="{{ route('events.index') }}">
                            <i class="fa-solid fa-calendar fa-fw me-1"></i>
                            Manage Events
                        </a>
                    </li>
                @endcan

                @auth
                    <li class="nav-item">
                        <a @class([
                               'nav-link',
                               'active' => Route::is(['orders*']),
                           ])
                           {{ Route::is('orders*') ? 'aria-current="page"' : '' }}
                           href="{{ route('orders.index') }}">
                            <i class="fa-solid fa-receipt fa-fw me-1"></i>
                            My Orders
                        </a>
                    </li>
                @endauth

                <!-- TODO: add help menu back
                <li class="nav-item">
                    <a @class([
                           'nav-link',
                           'active' => Route::is('help*'),
                       ])
                       {{ Route::is('help*') ? 'aria-current="page"' : '' }}
                       href="{{ route('help.index') }}">
                        <i class="fa-solid fa-question-circle fa-fw me-1"></i>
                        Help
                    </a>
                </li>
                -->
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @can('manage own events')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-plus fa-fw"></i>
                            <span class="d-lg-none ms-1">New</span>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('events.create') }}">
                                    <i class="fa-solid fa-calendar-plus fa-fw"></i>
                                    <span class="d-lg-inline d-none ms-1">New </span>Event
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('organisations.create') }}">
                                    <i class="fa-solid fa-person-circle-plus fa-fw"></i>
                                    <span class="d-lg-inline d-none ms-1">New </span>Organisation
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto">
                        <div class="vr d-none d-lg-flex h-100 mx-lg-2 text-white"></div>
                        <hr class="d-lg-none my-2 text-white-50">
                    </li>
                @endcan

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                            id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                            data-bs-display="static">
                        <i class="fa-solid fa-circle-half-stroke fa-fw theme-icon-active"></i>
                        <span class="d-lg-none ms-1">Toggle theme</span>
                    </a>

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

                @auth
                    <li class="nav-item dropdown">
                        <a @class([
                               'nav-link',
                               'dropdown-toggle',
                                'active' => Route::is(['account.show-own', 'account.edit'])
                           ]) href="#"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="fa-solid fa-user-lock me-1"></i>
                            Signed in as {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a @class([
                                       'dropdown-item',
                                       'active' => Route::is('account.show-own')
                                   ]) {{ Route::is('account.show-own') ? 'aria-current="page"' : '' }}
                                   href="{{ route('account.show-own') }}">
                                    <i class="fa-solid fa-user-gear fa-fw me-1"></i>
                                    My Account
                                </a>
                            </li>

                            <li class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item" href="#" id="logout_button">
                                    <i class="fa-solid fa-key fa-fw me-1"></i>
                                    Sign Out
                                </a>
                                <form id="logout_form" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="fa-solid fa-key me-2"></i>
                            Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">
                            <i class="fa-solid fa-pencil me-2"></i>
                            Sign Up
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="nav-scroller shadow mb-3">
    @if(Route::is('help*'))
        <div class="container">
            <nav class="p-2" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">All Help</li>
                </ol>
            </nav>
        </div>
    @elseif(Route::is('home*', 'event.*', 'orders*'))
        <div class="container">
            <nav class="p-2" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    @unless(Route::is('orders*'))
                        <li @class([
                            'breadcrumb-item',
                            'active' => Route::is('home')
                        ])>
                            @unless(Route::is('home'))
                                <a href="{{ route('home') }}">Upcoming Events</a>
                            @else
                                Upcoming Events
                            @endunless
                        </li>
                    @endunless
                    @if(Route::is('home.event', 'event.*'))
                        <li @class([
                            'breadcrumb-item',
                            'active' => Route::is('home.event')
                        ])>
                            @if(Route::is('event.*'))
                                <a href="{{ route('home.event', $event) }}">{{ $event->name }}</a>
                            @else
                                {{ $event->name }}
                            @endif
                        </li>

                        @if(Route::is('event.tickets.*'))
                            <li @class([
                                'breadcrumb-item',
                                'active'
                            ])>
                                Checkout
                            </li>
                        @endif
                    @elseif(Route::is('orders*'))
                        <li @class([
                            'breadcrumb-item',
                            'active' => Route::is('orders.index')
                        ])>
                            @unless(Route::is('orders.index'))
                                <a href="{{ route('orders.index') }}">My Orders</a>
                            @else
                                My Orders
                            @endunless
                        </li>

                        @if(Route::is('orders.show'))
                            <li @class([
                            'breadcrumb-item',
                            'active'
                            ])>
                                Order #{{ $order->id }}
                            </li>
                        @endif
                    @endif
                </ol>
            </nav>
        </div>
    @else
        <nav class="nav container" aria-label="Secondary navigation" data-bs-theme="light">
            @if(Route::is(['events*', 'organisations*']))
                <a @class([
                   'nav-link',
                   'active' => Route::is(['events.*'])
               ])
                   {{ Route::is(['events.*']) ?'aria-current="page"' :'' }}
                   href="{{ route('events.index') }}">
                    <i class="fa-solid fa-rectangle-list fa-fw"></i>
                    My Events
                </a>

                <a @class([
                   'nav-link',
                   'active' => Route::is('organisations*')
               ]) {{ Route::is('organisations*') ? 'aria-current="page"' : '' }}
                   href="{{ route('organisations.index') }}">
                    <i class="fa-solid fa-building fa-fw"></i>
                    My Organisations
                </a>
            @elseif(Route::is(['account.show-own', 'account.edit']))
                <a @class([
                   'nav-link',
                   'active' => Route::is(['account.show-own', 'account.edit'])
               ]) {{ ['account.show-own', 'account.edit'] ? 'aria-current="page"' : '' }}
                   href="{{ route('account.show-own') }}">
                    <i class="fa-solid fa-user-gear fa-fw me-1"></i>
                    My Account
                </a>
            @endif
        </nav>
    @endif
</div>
