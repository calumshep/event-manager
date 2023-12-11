@extends('layout.app')

@section('content')

    @include('components.status')

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h1 class="card-title">
                        {{ $creating ? "New" : null }} Ticket
                    </h1>

                    @if($creating)
                        <p class="text-muted">
                            Tickets are multipurpose - they could be different ticket types for the same event, they
                            could be for different individual events over a longer period (parent event), etc.
                        </p>
                    @endif

                    <form method="POST"
                          action="{{ $creating ?
                          route('events.tickets.store', $event) :
                          route('events.tickets.update', [$event, $ticket]) }}">
                        @csrf
                        {{ $creating ? null : method_field('PUT') }}

                        <fieldset @disabled($readonly)>
                            <div class="mb-3">
                                <label class="form-label" for="name">
                                    Ticket name<span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') ? old('name') : $ticket->name }}"
                                       class="form-control"
                                       autocomplete="off"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="time">Validity<span
                                            class="text-danger">*</span></label>
                                <input type="date"
                                       name="time"
                                       id="time"
                                       value="@if(old('time')){{ old('time') }}@elseif($ticket->time){{
                                       $ticket->time->format('Y-m-d') }}@endif"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description<span
                                            class="text-danger">*</span></label>
                                <textarea
                                        name="description"
                                        id="description"
                                        class="form-control"
                                        rows="2"
                                >{{ old('description') ?: $ticket->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="price">Price</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="pound-symbol">£</span>
                                    <input type="number"
                                           step="0.01"
                                           name="price"
                                           id="price"
                                           value="{{ old('price') ? number_format(old('price'), 2) :
                                           number_format($ticket->price/100, 2) }}"
                                           class="form-control">
                                </div>
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-between">
                            <a href="{{ (!$creating && !$readonly) ? route('events.tickets.show', [$event, $ticket]) :
                            route('events.show', $event) }}"
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
                                    <a href="{{ route('events.tickets.edit', [$event, $ticket]) }}" class="btn
                                    btn-primary">
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
                            Delete Ticket
                        </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete this ticket?
                    </div>

                    <div class="modal-footer">
                        <form method="POST" action="{{ route('events.tickets.destroy', [$event, $ticket]) }}">
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
