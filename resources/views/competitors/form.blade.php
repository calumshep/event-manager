@extends('layouts.app')

@section('content')

    @include('components.errors')

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-3">
                <div class="card-body">
                    <h1 class="card-title">
                        {{ $creating ?? "New" }} Competitor
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
                        {{ $creating ?: method_field('PUT') }}

                        <div class="mb-3">
                            <label class="form-label" for="name">Full name<span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') ? old('name') : $competitor->name }}"
                                   class="form-control"
                                   required
                                   @disabled($readonly)
                                   @readonly($readonly)>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="dob">Date of birth<span class="text-danger">*</span></label>
                            <input type="date"
                                   name="dob"
                                   id="dob"
                                   value="{{ old('dob') ? old('dob') : $competitor->dob->format('Y-m-d') }}"
                                   class="form-control"
                                   required
                                   @disabled($readonly)
                                   @readonly($readonly)>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="gender">Gender<span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-select" @disabled($readonly)>
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
                                   class="form-control"
                                   @disabled($readonly)
                                   @readonly($readonly)>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('competitors.index') }}" class="btn btn-secondary">&laquo; Back</a>

                            <div>
                                @if(!$readonly)
                                    <button type="reset" class="btn btn-outline-danger">Reset</button>
                                @endif()

                                @if($readonly)
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



@endsection
