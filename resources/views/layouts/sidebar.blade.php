<div class="flex-shrink-0">
    <ul class="nav nav-pills flex-column mb-auto">
        @include('layouts.partials.nav-standard')
    </ul>

    @hasanyrole('admin')
        <ul class="list-unstyled ps-0">
            <li class="border-top my-3"></li>
            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                        data-bs-toggle="collapse" data-bs-target="#admin-collapse" aria-expanded="false">
                    <i class="fa-solid fa-shield-halved fa-fw me-2"></i>
                    Admin
                </button>

                <div class="collapse" id="admin-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        @include('layouts.partials.nav-admin')
                    </ul>
                </div>
            </li>
        </ul>
    @endhasanyrole
</div>
