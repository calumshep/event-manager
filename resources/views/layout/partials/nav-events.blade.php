<li class="nav-item">
    <a href="{{ route('events.index') }}"
       class="d-inline-flex text-decoration-none rounded
       @if(Route::is('events*')) active @endif">
        <i class="fa-solid fa-rectangle-list fa-fw mt-1 me-2"></i>
        List Events
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('events.create') }}"
       class="d-inline-flex text-decoration-none rounded
       @if(Route::is('events.create')) active @endif">
        <i class="fa-solid fa-calendar-plus fa-fw mt-1 me-2"></i>
        New Event
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('organisations.index') }}"
       class="d-inline-flex text-decoration-none rounded
       @if(Route::is('organisations*')) active @endif">
        <i class="fa-solid fa-building fa-fw mt-1 me-2"></i>
        Organisations
    </a>
</li>
