<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <span class="navbar-brand mb-0 h1">Training Manager</span>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary">Overview</a></li>
                <li><a href="#" class="nav-link px-2 link-dark">Sessions</a></li>
                <li><a href="#" class="nav-link px-2 link-dark">Users</a></li>
            </ul>

            @auth
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        Calum Shepherd
                        <img src="https://scontent.fgla1-1.fna.fbcdn.net/v/t39.30808-6/242193848_2364211513748201_7568626738875509754_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=KNOS12FzeaAAX-cOgeE&_nc_ht=scontent.fgla1-1.fna&oh=00_AT8lfyT0uq7yZ4euLaJ938i8mZLQBUrLcAY9W9SR6fZKkQ&oe=62D2D596" alt="mdo" width="32" height="32" class="rounded-circle ms-3 mb-1">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#">Account</a></li>

                        <div class="dropdown-divider"></div>

                        <form class="px-3" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-secondary" type="submit">Sign out</button>
                        </form>
                    </ul>
                </div>
            @else
                <div class="text-end">
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary">Sign-up</a>
                </div>
            @endauth
        </div>
    </div>
</header>
