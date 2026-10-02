@extends('layouts.app')

@section('title', 'User management')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h2>User management</h2>
        <a href="{{ route('users.create') }}" class="btn-new">+ Create user</a>
    </div>

    @if (session('success'))
        <div class="success-alert"><span>{{ session('success') }}</span></div>
    @endif

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            {{ $user->name }}
                            @unless ($user->is_active)
                                <span class="user-inactive-label">Inactive</span>
                            @endunless
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->name }}</td>
                        <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="user-actions">
                            <a href="{{ route('users.edit', $user) }}" class="btn-edit">Edit</a>

                            @if ($user->is_active && $user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Deactivate this user? Their ticket and comment history will be preserved.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete-user">Deactivate</button>
                                </form>
                            @elseif (! $user->is_active)
                                <form method="POST" action="{{ route('users.activate', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-reactivate-user">Reactivate</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="nt-3">{{ $users->links() }}</div>
</div>
@endsection
