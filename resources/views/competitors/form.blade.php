@extends('layouts.app')

@section('content')

    @include('components.status')

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h1 class="card-title">
                        {{ $creating ? "New" : null }} Competitor
                    </h1>

                    @if($creating)
                        <p class="text-muted">
                            You only have to do this once. Then, you can use this competitor to enter multiple
                            competitions.
                        </p>
                    @endif

                    <form method="POST"
                          action="{{ $creating ? route('competitors.store') : route('competitors.update', $competitor) }}">
                        @csrf
                        {{ $creating ? null : method_field('PUT') }}

                        <fieldset @disabled($readonly)>
                            <div class="mb-3">
                                <label class="form-label" for="name">Full name<span class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') ? old('name') : $competitor->name }}"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="dob">Date of birth<span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                       name="dob"
                                       id="dob"
                                       value="@if(old('dob')){{ old('dob') }}@elseif($competitor->dob){{ $competitor->dob->format('Y-m-d') }}@endif"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="gender">Gender<span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-select">
                                    @foreach($genders as $gender)
                                        <option value="{{ $gender }}"
                                            @selected(old('gender') ? old('gender') == $gender : $competitor->gender == $gender)>
                                            {{ ucfirst($gender) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="reg_id">BASS number</label>
                                <input type="text"
                                       name="reg_id"
                                       id="reg_id"
                                       value="{{ old('reg_id') ? old('reg_id') : $competitor->reg_id }}"
                                       class="form-control">
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-between">
                            <a href="{{ (!$creating && !$readonly) ? route('competitors.show', $competitor) : route('competitors.index') }}"
                               class="btn btn-secondary">
                                &laquo; Back
                            </a>

                            <div>
                                @if(!$readonly)
                                    <button type="reset" class="btn btn-outline-danger">Reset</button>
                                @endif()

                                @if($readonly)
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                        <i class="fa-solid fa-trash me-2"></i>Delete
                                    </button>
                                    <a href="{{ route('competitors.edit', $competitor) }}" class="btn btn-primary">
                                        <i class="fa-solid fa-pencil me-2"></i>Edit
                                    </a>
                                @else
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-save me-2"></i>Save
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($readonly)
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">
                            Delete Competitor
                        </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete this competitor?
                    </div>

                    <div class="modal-footer">
                        <form method="POST" action="{{ route('competitors.destroy', $competitor) }}">
                            @csrf
                            @method('DELETE')

                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fa-solid fa-close me-2"></i>Close
                            </button>

                            <button type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-trash me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
