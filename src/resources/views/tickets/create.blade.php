@extends('layouts.app')

@section('title', 'New Ticket')

@section('content')

<div class="table-container">

    <div class="table-header">
        <h2>New Ticket</h2>
    </div>

    <form method="POST" action="{{ route('tickets.store') }}">

        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>

            <input
                type="text"
                name="title"
                id="title"
                class="form-control"
                value="{{ old('title') }}"
            >
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>

            <textarea
                name="description"
                id="description"
                class="form-control"
                rows="5"
            >{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn-new">
            Create Ticket
        </button>

    </form>

</div>

@endsection