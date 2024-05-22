@extends('layout.app')

@section('head')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')

    <h1>{{ $creating ? "New" : null }} Ticket</h1>

    <p>
        Required fields are marked with an asterisk (<span class="text-danger">*</span>).
    </p>

    @include('components.status')

    <form method="POST"
          action="{{ $creating ?
              route('events.tickets.store', $event) :
              route('events.tickets.update', [$event, $ticket]) }}">
        @csrf
        {{ $creating ? null : method_field('PUT') }}

        <fieldset @disabled($readonly)>
            <!-- details -->
            <hr>
            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Ticket Details</h2>

                    <p>
                        These details help people identify this ticket.
                    </p>
                </div>

                <div class="col-lg-8">
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

                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label class="form-label" for="description">Description<span
                                    class="text-danger">*</span></label>
                            <input type="hidden"
                                   name="description"
                                   id="description"
                                   value="{{ old('description', $ticket->description) }}">

                            <div id="quill_editor">
                                {!! $ticket ? $ticket->description : '' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- config -->
            <hr>
            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Configuration</h2>

                    <p>
                        These settings allow you to customise how this ticket works.
                        <!--For more information, see our
                        <a href="{{ route('help.index') }}">help section</a>.-->
                    </p>
                    <p>
                        For a free ticket, set the Price to 0.00.
                    </p>
                </div>

                <div class="col-lg-8">
                    <div class="mb-3">
                        <label class="form-label" for="time">Validity<span class="text-danger">*</span></label>
                        <select class="form-select" name="time" id="time" required>
                            <option value disabled selected>Select a day...</option>

                            @foreach($event_days as $day)
                                @if($ticket->time)
                                    <option value="{{ $day->format('Y-m-d') }}"
                                            @selected(old('time', $ticket->time->format('Y-m-d')) == $day->format('Y-m-d'))>
                                        {{ $day->format('D d M Y') }}
                                    </option>
                                @else
                                    <option value="{{ $day->format('Y-m-d') }}"
                                            @selected(old('time') == $day->format('Y-m-d'))>
                                        {{ $day->format('D d M Y') }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        <div id="timeHelp" class="form-text">
                            You must specify a date which this ticket is valid for. If your event only spans a single
                            day, then select that day.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="price">Price<span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="pound-symbol">£</span>
                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   id="price"
                                   value="{{ old('price') ? number_format(old('price'), 2) :
                                       number_format($ticket->price/100, 2) }}"
                                   class="form-control"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="capacity">Capacity</label>
                        <input type="number"
                               step="1"
                               min="1"
                               name="capacity"
                               id="capacity"
                               value="{{ old('capacity', $ticket->capacity) }}"
                               class="form-control">

                        <div id="capacityHelp" class="form-text">
                            Leave blank for unlimited tickets.
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input"
                               type="checkbox"
                               role="switch"
                               id="show_remaining"
                               name="show_remaining"
                               @checked(old('show_remaining', $ticket->show_remaining))>
                        <label class="form-check-label" for="show_remaining">Show remaining tickets</label>
                        <div id="show_remainingHelp" class="form-text">
                            When enabled, the number of tickets remaining out of the total capacity above will be shown.
                            This setting is ignored for tickets with unlimited capacity.
                        </div>
                    </div>

                    <div class="mb-3">
                        <p>Collect:</p>

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   value="1"
                                   name="dietary"
                                   id="dietary"
                                   @checked(key_exists('dietary', $details))>
                            <label class="form-check-label" for="dietary">
                                Dietary Requirements
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   value="1"
                                   name="dob"
                                   id="dob"
                                   @checked(key_exists('dob', $details))>
                            <label class="form-check-label" for="dob">
                                Date of Birth
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   value="1"
                                   name="gender"
                                   id="gender"
                                   @checked(key_exists('gender', $details))>
                            <label class="form-check-label" for="gender">
                                Gender
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   value="1"
                                   name="gbr_no"
                                   id="gbr_no"
                                   @checked(key_exists('gbr_no', $details))>
                            <label class="form-check-label" for="gbr_no">
                                GBR registration number
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   value="1"
                                   name="club"
                                   id="club"
                                   @checked(key_exists('club', $details))>
                            <label class="form-check-label" for="club">
                                Club
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   value="1"
                                   name="university"
                                   id="university"
                                   @checked(key_exists('university', $details))>
                            <label class="form-check-label" for="university">
                                Higher Education Institution
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <hr>

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
                        <p>
                            Are you sure you want to delete this ticket?
                        </p>
                        <p class="text-danger">
                            You cannot undo this action.
                        </p>
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
                document.querySelector('#description').value = editor.root.innerHTML;
            });
        </script>
    @endunless
@endsection
