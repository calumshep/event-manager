@extends('layouts.app')

@section('content')

    @include('components.status')

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h1 class="card-title">
                        {{ $creating ? "New" : null }} Organisation
                    </h1>

                    <form method="POST"
                          action="{{ $creating ? route('organisations.store') : route('organisations.update', $organisation) }}">
                        @csrf
                        {{ $creating ? null : method_field('PUT') }}

                        <fieldset @disabled($readonly)>
                            <div class="mb-3">
                                <label class="form-label" for="name">Name<span class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') ? old('name') : $organisation->name }}"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="website">Website<span class="text-danger">*</span></label>
                                <input type="url"
                                       name="website"
                                       id="website"
                                       value="{{ old('website') ? old('website') : $organisation->website }}"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea name="description" id="description" class="form-control"
                                >{{ $organisation->description }}</textarea>
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-between">
                            <a href="{{ (!$creating && !$readonly) ? route('organisations.show', $organisation) : route('organisations.index') }}"
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
                                    <a href="{{ route('organisations.edit', $organisation) }}" class="btn btn-primary">
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
                            Delete Organisation
                        </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete this organisation?
                    </div>

                    <div class="modal-footer">
                        <form method="POST" action="{{ route('organisations.destroy', $organisation) }}">
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
