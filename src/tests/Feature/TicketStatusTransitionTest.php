<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketStatusTransitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_can_move_from_in_progress_to_resolved(): void
    {
        [$user, $ticket] = $this->createTicketWithStatus('In Progress');
        $resolved = $this->createStatus('Resolved', '#10B981', 4);

        $response = $this->actingAs($user)->patch(
            route('tickets.status.update', $ticket),
            ['status_id' => $resolved->id]
        );

        $response->assertRedirect(route('tickets.show', $ticket));

        $updatedTicket = $ticket->fresh();

        $this->assertSame($resolved->id, $updatedTicket->status_id);
        $this->assertNotNull($updatedTicket->resolved_at);
        $this->assertNull($updatedTicket->closed_at);
    }

    public function test_ticket_can_move_through_the_remaining_valid_transitions(): void
    {
        $transitions = [
            'Open' => 'In Progress',
            'In Progress' => 'Pending',
            'Pending' => 'In Progress',
            'Resolved' => 'Reopened',
            'Reopened' => 'In Progress',
        ];

        foreach ($transitions as $currentName => $nextName) {
            [$user, $ticket] = $this->createTicketWithStatus($currentName);
            $nextStatus = $this->createStatus($nextName, '#10B981', 4);

            $response = $this->actingAs($user)->patch(
                route('tickets.status.update', $ticket),
                ['status_id' => $nextStatus->id]
            );

            $response->assertRedirect(route('tickets.show', $ticket));
            $this->assertSame($nextStatus->id, $ticket->fresh()->status_id);
        }
    }

    public function test_ticket_cannot_move_from_open_to_resolved(): void
    {
        [$user, $ticket] = $this->createTicketWithStatus('Open');
        $resolved = $this->createStatus('Resolved', '#10B981', 4);

        $response = $this->actingAs($user)->patch(
            route('tickets.status.update', $ticket),
            ['status_id' => $resolved->id]
        );

        $response->assertSessionHasErrors('status_id');
        $this->assertSame(
            TicketStatus::where('name', 'Open')->value('id'),
            $ticket->fresh()->status_id
        );
    }

    public function test_ticket_cannot_be_closed_manually_from_resolved(): void
    {
        [$user, $ticket] = $this->createTicketWithStatus('Resolved', [
            'resolved_at' => now()->subDays(8),
        ]);
        $closed = $this->createStatus('Closed', '#6B7280', 5);

        $response = $this->actingAs($user)->patch(
            route('tickets.status.update', $ticket),
            ['status_id' => $closed->id]
        );

        $response->assertSessionHasErrors('status_id');
        $updatedTicket = $ticket->fresh();

        $this->assertSame(
            TicketStatus::where('name', 'Resolved')->value('id'),
            $updatedTicket->status_id
        );
        $this->assertNull($updatedTicket->closed_at);
    }

    private function createTicketWithStatus(string $statusName, array $attributes = []): array
    {
        $user = User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => 'Technician'])->id,
        ]);
        $status = $this->createStatus($statusName, '#2fd9eb', 1);

        $ticket = Ticket::factory()->create(array_merge([
            'created_by' => $user->id,
            'status_id' => $status->id,
            'priority_id' => Priority::firstOrCreate([
                'name' => 'Medium',
            ], [
                'color' => '#F59E0B',
            ])->id,
            'category_id' => Category::firstOrCreate([
                'name' => 'Software',
            ], [
                'description' => 'Test category',
            ])->id,
        ], $attributes));

        return [$user, $ticket];
    }

    private function createStatus(string $name, string $color, int $sortOrder): TicketStatus
    {
        return TicketStatus::firstOrCreate([
            'name' => $name,
        ], [
            'color' => $color,
            'sort_order' => $sortOrder,
        ]);
    }
}