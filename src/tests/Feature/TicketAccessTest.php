<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_only_list_and_view_tickets_they_created(): void
    {
        $user = $this->createUserWithRole('User');
        $anotherUser = $this->createUserWithRole('User');
        $ownTicket = Ticket::factory()->create([
            'created_by' => $user->id,
            'title' => 'My support request',
        ]);
        $anotherTicket = Ticket::factory()->create([
            'created_by' => $anotherUser->id,
            'title' => 'Someone else support request',
        ]);

        $this->actingAs($user)
            ->get(route('tickets.index'))
            ->assertOk()
            ->assertSee('My tickets')
            ->assertSee('My support request')
            ->assertDontSee('Someone else support request');

        $this->get(route('tickets.show', $ownTicket))
            ->assertOk()
            ->assertDontSee('data-modal-open="edit-ticket-modal"', false)
            ->assertDontSee('id="edit-ticket-modal"', false);

        $this->get(route('tickets.show', $anotherTicket))
            ->assertForbidden();
    }

    public function test_user_cannot_update_ticket_details_or_status(): void
    {
        $user = $this->createUserWithRole('User');
        $ticket = Ticket::factory()->create(['created_by' => $user->id]);
        $inProgress = TicketStatus::firstOrCreate(
            ['name' => 'In Progress'],
            ['color' => '#F59E0B', 'sort_order' => 2]
        );

        $this->actingAs($user)
            ->patch(route('tickets.update', $ticket), [])
            ->assertForbidden();

        $this->patch(route('tickets.status.update', $ticket), [
            'status_id' => $inProgress->id,
        ])->assertForbidden();

        $this->assertSame('Open', $ticket->fresh()->status->name);
    }

    public function test_technician_and_administrator_can_view_all_tickets_and_manage_status(): void
    {
        $owner = $this->createUserWithRole('User');
        $open = TicketStatus::firstOrCreate(
            ['name' => 'Open'],
            ['color' => '#2fd9eb', 'sort_order' => 1]
        );
        $inProgress = TicketStatus::firstOrCreate(
            ['name' => 'In Progress'],
            ['color' => '#F59E0B', 'sort_order' => 2]
        );
        $firstTicket = Ticket::factory()->create([
            'created_by' => $owner->id,
            'status_id' => $open->id,
            'title' => 'First request',
        ]);
        $secondTicket = Ticket::factory()->create([
            'created_by' => $owner->id,
            'status_id' => $open->id,
            'title' => 'Second request',
        ]);

        foreach (['Technician', 'Administrator'] as $roleName) {
            $staff = $this->createUserWithRole($roleName);

            $this->actingAs($staff)
                ->get(route('tickets.index'))
                ->assertOk()
                ->assertSee('First request')
                ->assertSee('Second request');

            $this->patch(route('tickets.status.update', $firstTicket), [
                'status_id' => $inProgress->id,
            ])->assertRedirect(route('tickets.show', $firstTicket));

            $firstTicket->refresh();
            $this->assertSame($inProgress->id, $firstTicket->status_id);
            $firstTicket->update(['status_id' => $open->id]);
        }
    }

    private function createUserWithRole(string $roleName): User
    {
        return User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => $roleName])->id,
        ]);
    }
}
