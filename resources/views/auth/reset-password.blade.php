@extends('layout.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            @include('components.status')

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <h1 class="h3 mb-3 text-center">Password reset</h1>

                <!-- Email -->
                <div class="form-floating mb-3">
                    <input type="email"
                           name="email"
                           class="form-control"
                           id="email"
                           placeholder="name@example.com"
                           value="{{ old('email', $request->email) }}"
                           required>
                    <label for="email">Email Address</label>
                </div>

                <!-- Password -->
                <div class="form-floating mb-3">
                    <input type="password"
                           name="password"
                           class="form-control"
                           id="password"
                           required
                           autofocus>
                    <label for="password">New Password</label>
                </div>

                <!-- Confirm Password -->
                <div class="form-floating mb-3">
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           id="password_confirmation"
                           required>
                    <label for="password_confirmation">Confirm New Password</label>
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Reset password</button>
            </form>
        </div>
    </div>

@endsection
