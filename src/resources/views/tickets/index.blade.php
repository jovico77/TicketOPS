@extends('layouts.app')

@section('title', 'Tickets')

@section('content')

<div class="table-container">

<form method="GET" action="{{ route('tickets.index') }}">

    <div class="table-header">

        <h2>{{ auth()->user()->role->name === 'User' ? 'My tickets' : 'Tickets' }}</h2>

        <div class="table-input-search">
            <input type="text" name="search" class="form-control" placeholder="Search tickets..." value="{{ request('search') }}">
        </div>

        @if (session('success'))
        <div id="success-alert" class="success-alert">
            <span>{{ session('success') }}</span>
            <button onclick="closeAlert()" aria-label="Cerrar">×</button>
        </div>
        @endif

        <a href="{{ route('tickets.create') }}" class="btn-new">
            + New Ticket
        </a>
        @if (auth()->user()->role->name === 'Administrator')
            <a href="{{ route('tickets.trash') }}" class="btn-filter">
                Trash
            </a>
        @endif

    </div>

    <div class="table-filter">

        <div class="filter-group">
            <label for="status">Status:</label>

            <select name="status" id="status" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="Open" {{ request('status') === 'Open' ? 'selected' : '' }}>
                    Open
                </option>
                <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>
                    In Progress
                </option>
                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option value="Resolved" {{ request('status') === 'Resolved' ? 'selected' : '' }}>
                    Resolved
                </option>
                <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>
                    Closed
                </option>
                <option value="Reopened" {{ request('status') === 'Reopened' ? 'selected' : '' }}>
                    Reopened
                </option>
            </select>
        </div>

        <div class="filter-group">
            <label for="priority">Priority:</label>

            <select name="priority" id="priority" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="Low" {{ request('priority') === 'Low' ? 'selected' : '' }}>
                    Low
                </option>
                <option value="Medium" {{ request('priority') === 'Medium' ? 'selected' : '' }}>
                    Medium
                </option>
                <option value="High" {{ request('priority') === 'High' ? 'selected' : '' }}>
                    High
                </option>
                <option value="Critical" {{ request('priority') === 'Critical' ? 'selected' : '' }}>
                    Critical
                </option>
            </select>
        </div>

        <div class="filter-group">
            <label for="category">Category:</label>

            <select name="category" id="category" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="Hardware" {{ request('category') === 'Hardware' ? 'selected' : '' }}>
                    Hardware
                </option>
                <option value="Software" {{ request('category') === 'Software' ? 'selected' : '' }}>
                    Software
                </option>
                <option value="Network" {{ request('category') === 'Network' ? 'selected' : '' }}>
                    Network
                </option>
                <option value="Accounts" {{ request('category') === 'Accounts' ? 'selected' : '' }}>
                    Accounts
                </option>
                <option value="Infrastructure" {{ request('category') === 'Infrastructure' ? 'selected' : '' }}>
                    Infrastructure
                </option>
                <option value="Other" {{ request('category') === 'Other' ? 'selected' : '' }}>
                    Other
                </option>
            </select>
        </div>

        <button type="submit" class="btn-filter">
            Filter
        </button>

    </div>

</form>

<div class="table-scroll">
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
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        @foreach($tickets as $ticket)

            <tr draggable="true" class="sortable-row">
                <td>
                    <a href="{{ route('tickets.show', $ticket) }}" class="ticket-link">
                        {{ $ticket->ticket_number }}
                    </a>
                </td>

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

                <td>
                @if (auth()->user()->role->name === 'Administrator')
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete" title="Move ticket to trash" aria-label="Move ticket to trash" onclick="return confirm('Move this ticket to trash?')">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path fill="currentColor" d="M9 3h6l1 2h5v2H3V5h5l1-2Zm-3 6h12l-1 12H7L6 9Zm3 2v8h2v-8H9Zm4 0v8h2v-8h-2Z"/>
                            </svg>
                        </button>
                    </form>
                @endif

                </td>
            </tr>

        @endforeach

        </tbody>
    </table>
</div>
    <div class="nt-3">
        {{ $tickets->links() }}
    </div>

</div>

@endsection
