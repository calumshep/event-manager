@extends('layout.app')

@section('head')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-baseline">
        <h1>{{ $creating ? "New" : null }} Event</h1>

        @unless($creating)
            <a class="btn btn-success" href="{{ route('events.sales', $event) }}">
                <i class="fa-solid fa-sterling-sign me-2"></i>Ticket Sales
            </a>
        @endunless
    </div>

    <p>
        Required fields are marked with an asterisk (<span class="text-danger">*</span>).
    </p>

    @if($creating)
        <p class="text-muted">
            Once you have created the event, you can then add different tickets for it, including free,
            paid, and discounted tickets.
        </p>
    @endif

    @include('components.status')

    <form method="POST"
          action="{{ $creating ? route('events.store') : route('events.update', $event) }}">
        @csrf
        {{ $creating ? null : method_field('PUT') }}

        <fieldset @disabled($readonly)>
            <!-- basics -->
            <hr>
            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Basics</h2>

                    <p>
                        These basic details help people find your event.
                    </p>
                </div>

                <div class="col-lg-8">
                    <div class="mb-3">
                        <label class="form-label" for="name">Event name<span
                                class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') ? old('name') : $event->name }}"
                               class="form-control"
                               autocomplete="off"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="org">Event organiser<span
                                class="text-danger">*</span></label>
                        <select name="org" id="org" class="form-select" required>
                            @forelse($orgs as $org)
                                <option value="{{ $org->id }}"
                                    @selected(old('org') ? old('org') == $org->id : $event->org == $org->id)>
                                    {{ $org->name }}
                                </option>
                            @empty
                                <option selected disabled>No organisations</option>
                            @endforelse
                        </select>

                        @if($creating)
                            <small class="form-text text-muted">
                                If you have not already, you must <a href="{{ route('organisations.create') }}">create
                                an organisation</a> to assign this event to.
                            </small>
                        @endif
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

                    @if(!$readonly || $event->end_date)
                        <div class="mb-3">
                            <label class="form-label" for="end">End date</label>
                            <input type="date"
                                   name="end"
                                   id="end"
                                   value="@if(old('end')){{ old('end') }}@elseif($event->end){{ $event->end->format('Y-m-d') }}@endif"
                                   class="form-control">
                            <small class="form-text">Leave blank for events no longer than 1 day.</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- details -->
            <hr>
            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Details</h2>

                    <p>
                        The short description appears on the upcoming events dashboard and should be a short 'sell' of
                        your event. The long description will be shown on the event page and should include full
                        details.
                    </p>
                </div>

                <div class="col-lg-8">
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
                        <input type="hidden" name="long_desc" id="long_desc">

                        <div id="quill_editor">
                            {!! $event ? $event->long_desc : '' !!}
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        @unless($creating)
            <!-- tickets -->
            <hr>

            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Tickets</h2>

                    <p>
                        Tickets allow your attendees to register or pay for the event. Add tickets for different days,
                        different price points, concessions, extras you're selling, etc.
                        <strong>Your event should have at least one ticket type.</strong>
                    </p>
                </div>

                <div class="col-lg-8">
                    <p>
                        <a href="{{ route('events.tickets.create', $event)  }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-2"></i>New Ticket
                        </a>
                    </p>

                    <table class="table table-hover table-striped border card-text">
                        <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Time</th>
                            <th>Price</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($event->tickets as $ticket)
                            <tr>
                                <td><a href="{{ route('events.tickets.show', [$event, $ticket]) }}">{{
                                        $ticket->name
                                        }}</a></td>
                                <td>{{ $ticket->time->format('d/m/Y') }}</td>
                                <td>£{{ number_format($ticket->price/100, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    No tickets found for this event.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endunless

        <hr>

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

    @if($readonly)
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">
                            Delete Event
                        </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Are you sure you want to delete this event?
                        </p>
                        <p class="text-danger">
                            You cannot undo this action.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <form method="POST" action="{{ route('events.destroy', $event) }}">
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

@section('scripts')
    @unless($readonly)
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
            let editor = new Quill('#quill_editor', {
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'header': [3,4] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ],
                },
                theme: 'snow',
            });

            editor.on('text-change', () => {
                document.querySelector('#long_desc').value = editor.root.innerHTML;
            });
        </script>
    @endunless
@endsection
