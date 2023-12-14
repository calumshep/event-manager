@extends('layout.app')

@section('content')

    <div class="text-center">
        <h1>Help & Support</h1>

        <p class="lead mb-4">
            We want to make {{ config('app.name') }} work for you - we're here to help.
        </p>

        <div class="input-group mb-3 w-75 mx-auto">
            <div class="form-floating">
                <input type="search" class="form-control" name="search" id="search" placeholder="How to create an event...">
                <label for="search">Search help articles...</label>
            </div>
            <button type="button" class="btn btn-outline-primary">
                <i class="fa-solid fa-magnifying-glass fa-xl mx-1"></i>
                <span class="d-none">Search</span>
            </button>
        </div>

    </div>

    <hr>

    <div class="text-center py-3">
        <h2 class="h3">Help Articles</h2>

        <div class="row row-cols-1 row-cols-md-3 g-4 mb-3 w-75 mx-auto">
            @forelse($categories as $category)
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body">
                            <a href="#" class="link-underline link-underline-opacity-0 link-underline-opacity-75-hover">
                                <h3 class="h5 card-title stretched-link">{{ $category->name }}</h3>
                            </a>
                            <p class="card-text">
                                <i class="fa-solid {{ $category->icon }} fa-4x py-2"></i>
                            </p>
                            <p class="card-text">{{ $category->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p>
                    Nothing else here yet!
                </p>
            @endforelse
        </div>
    </div>

    <hr>

    <div class="py-3">
        <h2 class="text-center h3">Get Support</h2>

        <p>Need more help? We're here for you.</p>
    </div>

@endsection
