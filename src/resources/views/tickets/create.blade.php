@extends('layouts.app')

@section('title', 'New Ticket')

@section('content')

    <form method="POST" action="{{ route('tickets.store') }}" class="ticket-form">

        @csrf

        <div class="mb-3">
            
            <div class="table-header create-ticket-header">
                <h2>New Ticket</h2>
            </div>

            <label for="title" class="form-label">Title</label>

            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>

            <textarea name="description" id="description" class="form-control" rows="5">{{ old('description') }}</textarea>
        </div>

        <div class="filter-group filter-group-div">
            <label for="priority">Priority:</label>

            <select name="priority_id" id="priority" class="form-select form-select-sm">
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->id }}">
                        {{ $priority->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group filter-group-div">
            <label for="category">Category:</label>

            <select name="category_id" id="category" class="form-select form-select-sm">
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

                <div class="filter-group filter-group-div">
            <label for="category">Subcategory:</label>

            <select name="subcategory_id" id="subcategory" class="form-select form-select-sm">
                <option value="">Select a subcategory</option>
            </select>
        </div>

        <button type="submit" class="btn-new create-ticket-btn">
            Create Ticket
        </button>

    </form>

@endsection