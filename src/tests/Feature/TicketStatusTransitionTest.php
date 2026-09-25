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
        $user = User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => 'User'])->id,
        ]);
        $inProgress = TicketStatus::create([
            'name' => 'In Progress',
            'color' => '#F59E0B',
            'sort_order' => 2,
        ]);
        $resolved = TicketStatus::create([
            'name' => 'Resolved',
            'color' => '#10B981',
            'sort_order' => 4,
        ]);
        $priority = Priority::create([
            'name' => 'Medium',
            'color' => '#F59E0B',
        ]);
        $category = Category::create([
            'name' => 'Software',
            'description' => 'Test category',
        ]);
        $ticket = Ticket::factory()->create([
            'created_by' => $user->id,
            'status_id' => $inProgress->id,
            'priority_id' => $priority->id,
            'category_id' => $category->id,
        ]);

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
}