@extends('layouts.app')

@section('head')

    <style>
        #header {
            background-image: url('https://www.inthesnow.com/wp-content/uploads/2017/11/cg1.jpg');
            background-size: cover;
            background-position: center;
            min-height: 400px;
            max-height: 50vh;
            width: 100%
        }
    </style>

@endsection

@section('content')

    <div id="header"></div>

    <div class="container py-5 text-center">
        <h1 class="display-4">British Ski Racing</h1>

        <p class="lead">
            This site allows (parents of) ski racers to enter competitions in the UK, as well as providing a way for
            event managers to take entries and card payments for their competitions.
        </p>
    </div>

    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Upcoming Competitions</h2>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card h-100 shadow">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                             xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                             preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#55595c"></rect>
                            <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                        </svg>
                        <div class="card-body">
                            <h2 class="card-title h3">APR Twin Peaks</h2>
                            <h3 class="card-subtitle fs-5 text-muted">Sat, 3 Jun 2023</h3>
                            <p class="card-text">
                                Firpark, SCO
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card h-100 shadow">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                             xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                             preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#55595c"></rect>
                            <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                        </svg>
                        <div class="card-body">
                            <h2 class="card-title h3">LSR Twin Peaks</h2>
                            <h3 class="card-subtitle fs-5 text-muted">Sun, 4 Jun 2023</h3>
                            <p class="card-text">
                                Hillend, SCO
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card h-100 shadow">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                             xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                             preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#55595c"></rect>
                            <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                        </svg>
                        <div class="card-body">
                            <h2 class="card-title h3">GBR Indoor 3</h2>
                            <h3 class="card-subtitle fs-5 text-muted">Sat, 10 Jun 2023</h3>
                            <p class="card-text">
                                Chill Factore, ENG
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h2>How Do I Enter?</h2>

        <div class="row">
            <div class="col-md-4">
                <p>
                    Firstly, you (the person paying for entries) will need an account to log in. You can create one for
                    free in seconds.
                </p>
                <p>
                    Next, you will need to add entrants you want to enter in to competitions to your account. Again,
                    this takes seconds, and you only have to do this once.
                </p>
                <p>
                    Finally, you just need to find the competition you want to enter, and pay for your entries!
                </p>
            </div>

            <div class="col-md-8">
                <div class="card bg-light shadow">
                     <div class="card-body text-center">
                         <h3 class="card-title">Create Your Account</h3>
                         <p class="lead card-text">
                             Click the button below to sign up as a parent/guardian or entrant over 16. You can add
                             your child(ren) to your account later. Creating an account is free!
                         </p>
                         <a class="card-text btn btn-lg btn-primary" href="{{ route('register') }}">
                             <i class="fa-solid fa-pencil me-2"></i>Sign Up
                         </a>
                         <a class="card-text btn btn-lg btn-secondary" href="#">
                             <i class="fa-solid fa-envelope me-2"></i>Contact Us
                         </a>
                     </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <h2>Race Organisers</h2>

        <p class="lead">
            Are you interested in a better way to manage entries, take payments and import data into your timing
            software? We can do just that.
        </p>
        <p>
            We take 3% commission per payment processed from competition entrants. We use
            <a href="https://stripe.com/gb" target="_blank">Stripe</a> to process all payments, which automatically pays
            entry fees into your account (minus <a href="https://stripe.com/gb/pricing" target="_blank">Stripe's
            processing fees</a>). We then automatically invoice you for our small commission, a price worth paying to
            remove the headache from taking competition entries!
    </div>

@endsection
