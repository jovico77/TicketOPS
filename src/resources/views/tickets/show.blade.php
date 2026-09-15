@extends('layouts.app')

@section('title', $ticket->ticket_number)

@section('content')

<div class="ticket-detail">
    <div class="ticket-detail-header">
        <div>
            <div class="ticket-detail-navigation">
                <a href="{{ route('tickets.index') }}" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to tickets
                </a>
                <a href="{{ route('tickets.show', $ticket) }}" class="ticket-link">
                    <span class="ticket-number">{{ $ticket->ticket_number }}</span>
                </a>
            </div>

            <h1>{{ $ticket->title }}</h1>
            <button type="button" class="btn-edit">Edit</button>
            <span class="status status-{{ Str::slug($ticket->status->name) }}">
                {{ $ticket->status->name }}
            </span>
        </div>
    </div>

    <div class="ticket-detail-grid">
        <section class="ticket-detail-main">
            <div class="detail-card">
                <h2>Description</h2>
                <p class="ticket-description">{{ $ticket->description }}</p>
            </div>

            <div class="detail-card">
                <h2>Comments</h2>

                @forelse ($ticket->comments as $comment)
                    <article class="comment">
                        <div class="comment-header">
                            <strong>{{ $comment->user?->name ?? 'Unknown user' }}</strong>
                            <time datetime="{{ $comment->created_at->toISOString() }}">
                                {{ $comment->created_at->format('d/m/Y H:i') }}
                            </time>
                        </div>
                        <p>{{ $comment->message }}</p>
                    </article>
                @empty
                    <p class="empty-state">No comments have been added yet.</p>
                @endforelse
            </div>
        </section>

        <aside class="detail-card ticket-meta">
            <h2>Ticket information</h2>

            <dl>
                <div>
                    <dt>Priority</dt>
                    <dd>
                        <i class="priority-icon priority-{{ Str::slug($ticket->priority->name) }}"></i>
                        {{ $ticket->priority->name }}
                    </dd>
                </div>
                <div>
                    <dt>Category</dt>
                    <dd>{{ $ticket->category->name }}</dd>
                </div>
                <div>
                    <dt>Subcategory</dt>
                    <dd>{{ $ticket->subcategory?->name ?? 'Not specified' }}</dd>
                </div>
                <div>
                    <dt>Created by</dt>
                    <dd>{{ $ticket->creator?->name ?? 'Unknown' }}</dd>
                </div>
                <div>
                    <dt>Assigned technician</dt>
                    <dd>{{ $ticket->technician?->name ?? 'Unassigned' }}</dd>
                </div>
                <div>
                    <dt>Created</dt>
                    <dd>{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                @if ($ticket->resolved_at)
                    <div>
                        <dt>Resolved</dt>
                        <dd>{{ $ticket->resolved_at->format('d/m/Y H:i') }}</dd>
                    </div>
                @endif
                @if ($ticket->closed_at)
                    <div>
                        <dt>Closed</dt>
                        <dd>{{ $ticket->closed_at->format('d/m/Y H:i') }}</dd>
                    </div>
                @endif
            </dl>
        </aside>
    </div>
</div>

@endsection