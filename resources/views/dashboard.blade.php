@extends('layouts.app')

@section('content')

<h1 class="mb-3">Upcoming Events</h1>

<div class="d-flex align-items-start rounded shadow p-3 mb-3">
    <img src="https://placehold.co/50" class="rounded" alt="placeholder">

    <div class="ms-3">
        <h5>
            <a class="text-decoration-none" href="#">
                Event name &middot;
                <span class="text-muted fs-6">Sat 1 Jan 1970</span>
            </a>
        </h5>

        <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus quo
            dignissimos eius nulla dicta nam et mollitia cupiditate amet natus nisi
            reiciendis qui suscipit, perspiciatis id similique iure dolorem ratione!
        </p>

        <div class="d-flex align-items-baseline justify-content-between">
            <div>
                <a href="#">GBSki</a> &middot;
                <a href="#"><i class="fa-brands fa-square-facebook fa-fw"></i></a>
            </div>

            <a href="#" class="btn btn-primary">
                Enter<i class="fa-solid fa-angle-double-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

<div class="d-flex align-items-start rounded shadow p-3 mb-3">
    <img src="https://placehold.co/50" class="rounded" alt="placeholder">

    <div class="ms-3">
        <h5>
            <a class="text-decoration-none" href="#">
                Event name &middot;
                <span class="text-muted fs-6">Sun 2 Jan 1970</span>
            </a>
        </h5>

        <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus quo
            dignissimos eius nulla dicta nam et mollitia cupiditate amet natus nisi
            reiciendis qui suscipit, perspiciatis id similique iure dolorem ratione!
        </p>

        <div class="d-flex align-items-baseline justify-content-between">
            <div>
                <a href="#">GBSki</a> &middot;
                <a href="#"><i class="fa-brands fa-square-facebook fa-fw"></i></a>
            </div>

            <a href="#" class="btn btn-primary">
                Enter<i class="fa-solid fa-angle-double-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

@endsection
