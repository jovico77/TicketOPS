@extends('layouts.app')

@section('title', 'Create user')

@section('content')
<div class="ticket-form">
    <div class="create-ticket-header">
        <a href="{{ route('users.index') }}" class="back-link">Back to users</a>
        <h1>Create user</h1>
    </div>

    <form method="POST" action="{{ route('users.store') }}" class="user-form">
        @csrf

        <div class="filter-group-div">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="100" required>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="filter-group-div">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" maxlength="100" required>
            @error('email')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="filter-group-div">
            <label for="role_id">Role</label>
            <select id="role_id" name="role_id" class="form-select" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="filter-group-div">
            <label for="password">Temporary password</label>
            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password" required>
            @error('password')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="filter-group-div">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
        </div>

        <button type="submit" class="create-ticket-btn btn btn-primary">Create user</button>
    </form>
</div>
@endsection
