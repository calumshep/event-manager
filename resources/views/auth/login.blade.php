@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <h1 class="h3 mb-3 text-center">Please sign in</h1>

            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                <label for="floatingInput">Email Address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required autocomplete="current-password">
                <label for="floatingPassword">Password</label>
            </div>

            <div class="checkbox mb-3">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>

            <div class="flex text-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@endsection
