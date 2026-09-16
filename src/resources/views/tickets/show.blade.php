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
                <span class="ticket-number">{{ $ticket->ticket_number }}</span>
            </div>
            <h1>{{ $ticket->title }}</h1>
            <button type="button" class="btn-edit" data-modal-open="edit-ticket-modal">Edit</button>
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

    <div id="edit-ticket-modal" class="modal-backdrop {{ $errors->any() ? 'is-visible' : '' }}" data-modal>
        <div class="edit-ticket-modal" role="dialog" aria-modal="true" aria-labelledby="edit-ticket-title">
            <div class="modal-header">
                <h2 id="edit-ticket-title">Edit ticket - {{ $ticket->ticket_number }}</h2>
                <button type="button" class="modal-close" data-modal-close aria-label="Close">×</button>
            </div>

            <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    <div class="edit-field">
                        <label for="edit-title">Title</label>
                        <input type="text" name="title" id="edit-title" value="{{ old('title', $ticket->title) }}" required>
                    </div>

                    <div class="edit-field">
                        <label for="edit-description">Description</label>
                        <textarea name="description" id="edit-description" rows="5" required>{{ old('description', $ticket->description) }}</textarea>
                    </div>

                    <div class="edit-field">
                        <label for="edit-priority">Priority</label>
                        <select name="priority_id" id="edit-priority" required>
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->id }}" @selected(old('priority_id', $ticket->priority_id) == $priority->id)>
                                    {{ $priority->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="edit-field">
                        <label for="edit-category">Category</label>
                        <select name="category_id" id="edit-category" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $ticket->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="edit-field">
                        <label for="edit-subcategory">Subcategory</label>
                        <select name="subcategory_id" id="edit-subcategory">
                            <option value="">Select a subcategory</option>
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" @selected(old('subcategory_id', $ticket->subcategory_id) == $subcategory->id)>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="modal-cancel" data-modal-close>Cancel</button>
                    <button type="submit" class="btn-edit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection