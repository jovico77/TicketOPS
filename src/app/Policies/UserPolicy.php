<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role?->name === 'Administrator';
    }

    public function create(User $user): bool
    {
        return $user->role?->name === 'Administrator';
    }

    public function update(User $user, User $managedUser): bool
    {
        return $user->role?->name === 'Administrator';
    }

    public function deactivate(User $user, User $managedUser): bool
    {
        return $user->role?->name === 'Administrator'
            && $user->isNot($managedUser);
    }

    public function activate(User $user): bool
    {
        return $user->role?->name === 'Administrator';
    }
}
