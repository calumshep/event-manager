@extends('layout.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            @include('components.status')

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <h1 class="h3 mb-3 text-center">Password reset</h1>

                <p>
                    Forgot your password? No problem. Just let us know your email address and we will email you a
                    password reset link that
                    will allow you to choose a new one.
                </p>

                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="floatingInput"
                           placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                    <label for="floatingInput">Email Address</label>
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Send reset link</button>

                <div class="flex text-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Remembered your password? Sign in.') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
