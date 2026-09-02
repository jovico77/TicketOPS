@extends('layouts.app')

@section('title', 'Tickets')

@section('content')

<div class="table-container">

    <div class="table-header">
        <h2>Dashboard</h2>

        <div class="table-input-search">
            <input type="text" class="form-control" placeholder="Search tickets...">
        </div>

        <button class="btn-new">
            + Nuevo Ticket
        </button>
    </div>

<div class="table-filter">

    <div class="filter-group">
        <label for="status">Estado:</label>
        <select id="status" class="form-select form-select-sm">
            <option>Todos</option>
            <option>Open</option>
            <option>In Progress</option>
            <option>Pending</option>
            <option>Closed</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="priority">Prioridad:</label>
        <select id="priority" class="form-select form-select-sm">
            <option>Todas</option>
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
            <option>Critical</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="category">Categoría:</label>
        <select id="category" class="form-select form-select-sm">
            <option>Todas</option>
            <option>Hardware</option>
            <option>Software</option>
            <option>Red</option>
            <option>Cuenta</option>
        </select>
    </div>

</div>

    <table>
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Technician</th>
                <th>Created By</th>
            </tr>
        </thead>

        <tbody>

        @foreach($tickets as $ticket)

            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>

                <td>
                    <span class="status status-{{ Str::slug($ticket->status->name) }}">
                        {{ $ticket->status->name }}
                    </span>
                </td>

                <td>
                    <span>
                        <i class="priority-icon priority-{{ Str::slug($ticket->priority->name) }}"></i>
                    </span>
                </td>

                <td>{{ $ticket->category->name }}</td>
                <td>{{ $ticket->subcategory?->name ?? '-' }}</td>
                <td>{{ $ticket->technician?->name ?? 'Unassigned' }}</td>
                <td>{{ $ticket->creator?->name ?? 'Unknown' }}</td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>

@endsection