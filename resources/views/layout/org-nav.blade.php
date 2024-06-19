<nav class="nav nav-pills flex-md-column">
    <a @class([
        'nav-link',
        'active' => Route::is(['organisations.show','organisations.edit'])
    ]) @if(Route::is(['organisations.show','organisations.edit'])) aria-current="page" @endif
    href="{{ route('organisations.show', $organisation) }}">
        <i class="fa-solid fa-circle-info fa-fw me-2"></i>Organisation Details
    </a>

    <a @class([
        'nav-link',
        'active' => Route::is('organisations.team.*')
    ]) @if(Route::is('organisations.team.*')) aria-current="page" @endif
    href="{{ route('organisations.team.index', $organisation) }}">
        <i class="fa-solid fa-users-rectangle fa-fw me-2"></i>Team Members
    </a>
</nav>
