@extends('layouts.app')

@section('head')

    {!! htmlScriptTagJsApi() !!}

@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <h1 class="h3 mb-3 text-center">Sign up</h1>

            <div class="form-floating mb-3">
                <input type="text" name="name" class="form-control" id="floatingName" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
                <label for="floatingName">Full Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ old('email') }}" required>
                <label for="floatingInput">Email Address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required autocomplete="new-password">
                <label for="floatingPassword">Password</label>
                <small class="text-muted">Minimum of 8 charaters required.</small>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password_confirmation" class="form-control" id="floatingPasswordConf" placeholder="Password" required autocomplete="new-password">
                <label for="floatingPasswordConf">Confirm Password</label>
            </div>

            {!! htmlFormSnippet() !!}

            <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Register</button>

            <div class="flex text-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
