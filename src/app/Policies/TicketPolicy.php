<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->name, ['User', 'Technician', 'Administrator'], true);
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $this->isStaff($user) || (
            $user->role?->name === 'User'
            && $ticket->created_by === $user->id
        );
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->name, ['User', 'Technician', 'Administrator'], true);
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $this->isStaff($user);
    }

    public function updateStatus(User $user, Ticket $ticket): bool
    {
        return $this->update($user, $ticket);
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->role?->name === 'Administrator';
    }

    public function viewTrash(User $user): bool
    {
        return $user->role?->name === 'Administrator';
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->role?->name === 'Administrator';
    }

    private function isStaff(User $user): bool
    {
        return in_array($user->role?->name, ['Technician', 'Administrator'], true);
    }
}
