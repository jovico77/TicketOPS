<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTrashTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_soft_delete_and_restore_a_ticket(): void
    {
        $administrator = $this->createUserWithRole('Administrator');
        $ticket = Ticket::factory()->create();

        $this->actingAs($administrator)
            ->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertSee('Move to trash');

        $this->delete(route('tickets.destroy', $ticket))
            ->assertRedirect(route('tickets.index'));

        $this->assertSoftDeleted('tickets', ['id' => $ticket->id]);

        $this->get(route('tickets.trash'))
            ->assertOk()
            ->assertSee($ticket->ticket_number);

        $this->get(route('tickets.show', $ticket))
            ->assertNotFound();

        $this->patch(route('tickets.restore', $ticket->ticket_number))
            ->assertRedirect(route('tickets.trash'));

        $this->assertNotSoftDeleted('tickets', ['id' => $ticket->id]);
        $this->get(route('tickets.show', $ticket))->assertOk();
        $this->get(route('tickets.index'))
            ->assertOk()
            ->assertSee($ticket->ticket_number);
    }

    public function test_non_administrators_cannot_delete_or_restore_tickets_or_view_trash(): void
    {
        $ticket = Ticket::factory()->create();
        $deletedTicket = Ticket::factory()->create();
        $deletedTicket->delete();

        foreach (['User', 'Technician'] as $roleName) {
            $user = $this->createUserWithRole($roleName);

            $this->actingAs($user)
                ->get(route('tickets.trash'))
                ->assertForbidden();

            $this->delete(route('tickets.destroy', $ticket))
                ->assertForbidden();

            $this->patch(route('tickets.restore', $deletedTicket->ticket_number))
                ->assertForbidden();
        }

        $this->assertNotSoftDeleted('tickets', ['id' => $ticket->id]);
        $this->assertSoftDeleted('tickets', ['id' => $deletedTicket->id]);
    }

    private function createUserWithRole(string $roleName): User
    {
        return User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => $roleName])->id,
        ]);
    }
}
