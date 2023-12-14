@extends('layout.app')

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        @if($user->id == auth()->user()->id)
            <h1>Your Account</h1>
        @else
            <h1>Account</h1>
            <a href="#" class="btn btn-secondary">&laquo; Back</a>
        @endif
    </div>

    <p>
        Required fields are marked with an asterisk (<span class="text-danger">*</span>).
    </p>

    @include('components.status')

    <form method="POST" action="{{ route('account.update', $user) }}">
        @csrf
        @method('PUT')

        <fieldset @disabled(Route::is('account.show*'))>
            <hr>

            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Your Details</h2>
                    <p>We need these to identify you, and to send necessary emails to you.</p>
                </div>

                <div class="col-lg-8">
                    <div class="mb-3">
                        <label for="first_name">First Name<span class="text-danger">*</span></label>
                        <input type="text"
                               name="first_name"
                               id="first_name"
                               class="form-control"
                               value="{{ $user->first_name }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name">Last Name<span class="text-danger">*</span></label>
                        <input type="text"
                               name="last_name"
                               id="last_name"
                               class="form-control"
                               value="{{ $user->last_name }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email Address<span class="text-danger">*</span></label>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control"
                               value="{{ $user->email }}"
                               required>
                    </div>
                </div>
        </fieldset>

        @if(Route::is('account.edit') && $user->id == auth()->user()->id)
            <hr>

            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Update Password</h2>
                    <p>Enter and confirm a new password if you wish to change your current one.</p>
                </div>

                <div class="col-lg-8">
                    <div class="mb-3">
                        <label for="new_password">New Password</label>
                        <input type="password"
                               name="new_password"
                               id="new_password"
                               class="form-control"
                               autocomplete="new-password">
                        <small class="text-muted">Minimum of 8 charaters required.</small>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password"
                               name="new_password_confirmation"
                               id="new_password_confirmation"
                               class="form-control"
                               autocomplete="new-password">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Confirm Password</h2>
                    <p>Confirm your current password, so we know it's you making these changes.</p>
                </div>

                <div class="col-lg-8">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            Current password<span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               name="current_password"
                               id="current_password"
                               autocomplete="current_password"
                               class="form-control"
                               required>
                    </div>
                </div>
            </div>
        @endif

        <hr>

        <div class="d-flex justify-content-between">
            @if(Route::is('account.edit'))
                <a href="{{ route('account.show', $user) }}" class="btn btn-secondary">
                    &laquo; Cancel
                </a>

                <button type="reset" class="btn btn-outline-danger">
                    <i class="fa-solid fa-close me-2"></i> Reset
                </button>
            @else
                <div></div>
                <a href="{{ route('account.edit', $user) }}" class="btn btn-primary">
                    <i class="fa-solid fa-pencil me-2"></i> Edit
                </a>
            @endif
        </div>
    </form>

@endsection
