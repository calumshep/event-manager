@extends('layout.app')

@section('head')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('content')

    <h1>{{ $creating ? "New" : null }} Organisation</h1>

    <p>
        Required fields are marked with an asterisk (<span class="text-danger">*</span>).
    </p

    @include('components.status')

    <form method="POST"
          action="{{ $creating ? route('organisations.store') : route('organisations.update', $organisation) }}">
        @csrf
        {{ $creating ? null : method_field('PUT') }}

        <fieldset @disabled($readonly)>
            <!-- basics -->
            <hr>
            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Basics</h2>

                    <p>
                        These basic details help people identify your organisation.
                    </p>
                </div>

                <div class="col-lg-8">
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
                        <label class="form-label" for="website">Website<span
                                class="text-danger">*</span></label>
                        <input type="url"
                               name="website"
                               id="website"
                               value="{{ old('website') ? old('website') : $organisation->website }}"
                               class="form-control"
                               required>
                    </div>
                </div>
            </div>

            <!-- details -->
            <hr>
            <div class="row pt-3 mb-3">
                <div class="col-lg-4">
                    <h2 class="h5">Details</h2>

                    <p>
                        This tells people more about your organisation.
                    </p>
                </div>

                <div class="col-lg-8">
                    <div class="mb-3">
                        <label class="form-label" for="description">Description<span
                                class="text-danger">*</span></label>
                        <input type="hidden" name="description" id="description">

                        <div id="quill_editor">
                            {!! $organisation ? $organisation->description : '' !!}
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <hr>

        <div class="d-flex justify-content-between">
            <a href="{{ (!$creating && !$readonly) ? route('organisations.show', $organisation) : route('organisations.index')
             }}"
               class="btn btn-secondary">
                <i class="fa-solid fa-close me-2"></i>Cancel
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

    @if($readonly)
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">
                            &laquo; Back
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
