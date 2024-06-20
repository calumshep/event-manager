@extends('layout.sidebar-form')

@section('head')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('title', $organisation->name)

@section('sidebar')
    @include('layout.org-nav')
@endsection

@section('form')
    <!-- add user -->
    <div class="row pt-3 mb-3">
        <div class="col-lg-4">
            <h2 class="h5">Add Member</h2>

            <p>
                Add a user to your organisation's team so they can see its events.
            </p>
        </div>

        <div class="col-lg-8">
            <form method="POST" action="{{ route('organisations.team.add', $organisation) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="email">
                        Email address
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <input type="email"
                               class="form-control"
                               name="email"
                               id="email"
                               autocomplete="off"
                               required>

                        <button class="btn btn-outline-success" type="submit">
                            <i class="fa-solid fa-plus me-2"></i>Add
                        </button>
                    </div>

                    <small class="form-text">
                        Enter the email address of a user you wish to add to this organisation's team.
                    </small>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <!-- team -->
    <div class="row pt-3 mb-3">
        <div class="col-lg-4">
            <h2 class="h5">Current Team</h2>

            <p>
                These users have access to manage and see ticket sales for all events associated with this
                organisation.
            </p>
        </div>

        <div class="col-lg-8">
            <table class="table table-hover table-striped border card-text">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @forelse($organisation->team as $user)
                    <tr>
                        <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('organisations.team.remove', $organisation) }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button class="btn btn-link p-0" type="submit">
                                    <i class="fa-solid fa-user-minus me-2"></i>Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No other users have access.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
