@extends('layouts.app')

@section('title', 'Ticket trash')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>Ticket trash</h2>
        <a href="{{ route('tickets.index') }}" class="back-link">Back to tickets</a>
    </div>

    @if (session('success'))
        <div class="success-alert">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Title</th>
                    <th>Created by</th>
                    <th>Deleted at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->creator?->name ?? 'Unknown' }}</td>
                        <td>{{ $ticket->deleted_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form method="POST" action="{{ route('tickets.restore', $ticket->ticket_number) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-edit">Restore</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">The trash is empty.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="nt-3">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
