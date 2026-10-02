@extends('layouts.app')

@section('title', 'Edit user')

@section('content')
<div class="ticket-form">
    <div class="create-ticket-header">
        <a href="{{ route('users.index') }}" class="back-link">Back to users</a>
        <h1>Edit user</h1>
        @unless ($user->is_active)
            <span class="user-inactive-label">Inactive</span>
        @endunless
    </div>

    <form method="POST" action="{{ route('users.update', $user) }}" class="user-form">
        @csrf
        @method('PATCH')

        <div class="filter-group-div">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" maxlength="100" required>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="filter-group-div">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" maxlength="100" required>
            @error('email')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="filter-group-div">
            <label for="role_id">Role</label>
            <select id="role_id" name="role_id" class="form-select" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="filter-group-div">
            <label for="password">New password <span class="user-form-hint">(leave blank to keep current password)</span></label>
            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
            @error('password')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="filter-group-div">
            <label for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
        </div>

        <button type="submit" class="create-ticket-btn btn btn-primary">Save changes</button>
    </form>
</div>
@endsection
