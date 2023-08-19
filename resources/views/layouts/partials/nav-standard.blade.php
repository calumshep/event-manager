<li class="nav-item">
    <a href="{{ route(('dashboard')) }}"
       class="nav-link @if(Route::is('dashboard')) active @endif"
       @if(Route::is('dashboard')) aria-current="page" @endif>
        <i class="fa-solid fa-calendar-week fa-fw me-2"></i>
        Upcoming Events
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('competitors.index') }}"
       class="nav-link @if(Route::is('competitors*')) active @endif"
       @if(Route::is('competitors*')) aria-current="page" @endif>
        <i class="fa-solid fa-users-rectangle fa-fw me-2"></i>
        Your Competitors
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('account.show-own') }}"
       class="nav-link @if(Route::is('account.show-own') || (auth()->check() && strpos(url()->current(), 'account/'.auth()->user()->id))) active @endif"
       @if(Route::is('account.show-own')) aria-current="page" @endif>
        <i class="fa-solid fa-user-gear fa-fw me-2"></i>
        Your Account
    </a>
</li>
