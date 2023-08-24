@extends('layouts.app')

@section('content')

    @include('components.status')

    <div class="card shadow mb-3">
        <div class="card-body">
            <h1 class="card-title">
                {{ $creating ? "New" : null }} Event
            </h1>

            @if($creating)
                <p class="text-muted">
                    Once you have created the event, you can thenadd different tickets for it, including free,
                    paid, and discounted tickets.
                </p>
            @endif
            <p class="text-muted">
                The short description appears on the upcoming events dashboard and should be a short 'sell' of your
                event. The long description will be shown on the event page and should include full details.
            </p>

            <form method="POST"
                  action="{{ $creating ? route('events.store') : route('events.update', $event) }}">
                @csrf
                {{ $creating ? null : method_field('PUT') }}

                <fieldset @disabled($readonly)>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="name">Event name<span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') ? old('name') : $event->name }}"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="start">Start date<span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                       name="start"
                                       id="start"
                                       value="@if(old('start')){{ old('start') }}@elseif($event->start){{ $event->start->format('Y-m-d') }}@endif"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="end">End date</label>
                                <input type="date"
                                       name="end"
                                       id="end"
                                       value="@if(old('end')){{ old('end') }}@elseif($event->end){{ $event->end->format('Y-m-d') }}@endif"
                                       class="form-control">
                                <small class="form-text">Leave empty for events no longer than 1 day.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="short_desc">Short description<span
                                        class="text-danger">*</span></label>
                                <textarea
                                    name="short_desc"
                                    id="short_desc"
                                    class="form-control"
                                    rows="2"
                                >{{ old('short_desc') ?: $event->short_desc }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="long_desc">Long description<span
                                        class="text-danger">*</span></label>
                                <textarea
                                    name="long_desc"
                                    id="long_desc"
                                    class="form-control"
                                    rows="4"
                                >{{ old('long_desc') ?: $event->long_desc }}</textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="d-flex justify-content-between">
                    <a href="{{ (!$creating && !$readonly) ? route('events.show', $event) : route('events.index') }}"
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

                            <a href="{{ route('events.edit', $event) }}" class="btn btn-primary">
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

    @if($readonly)
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">
                            Are you sure you want to delete this competitor?
                        </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        TODO
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-close me-2"></i>Close
                        </button>

                        <button type="button" class="btn btn-danger">
                            <i class="fa-solid fa-trash me-2"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
