<nav class="nav nav-pills flex-column">
    <a @class([
        'nav-link',
        'active' => Route::is(['events.show','events.edit'])
    ]) @if(Route::is(['events.show','events.edit'])) aria-current="page" @endif
    href="{{ route('events.show', $event) }}">
        <i class="fa-solid fa-magnifying-glass fa-fw me-2"></i>Event Details
    </a>

    <a @class([
        'nav-link',
        'active' => Route::is('events.tickets*')
    ]) @if(Route::is('events.tickets*')) aria-current="page" @endif
    href="{{ route('events.tickets.index', $event) }}">
        <i class="fa-solid fa-ticket fa-fw me-2"></i>Manage Tickets
    </a>

    <a @class([
        'nav-link',
        'active' => Route::is('events.sales')
    ]) @if(Route::is('events.sales')) aria-current="page" @endif
    href="{{ route('events.sales', $event) }}">
        <i class="fa-solid fa-pound-sign fa-fw me-2"></i>Tickets Sales
    </a>

    <a @class([
        'nav-link',
        'active' => Route::is('events.attendees')
    ]) @if(Route::is('events.attendees')) aria-current="page" @endif
    href="{{ route('events.attendees', $event) }}">
        <i class="fa-solid fa-people-group fa-fw me-2"></i>Attendees
    </a>
</nav>
