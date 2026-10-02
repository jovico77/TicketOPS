<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CloseResolvedTicketsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_closes_tickets_resolved_for_at_least_seven_days(): void
    {
        $resolved = $this->createStatus('Resolved', '#10B981', 4);
        $closed = $this->createStatus('Closed', '#6B7280', 5);

        $ticket = Ticket::factory()->create([
            'status_id' => $resolved->id,
            'resolved_at' => now()->subDays(8),
        ]);

        $this->artisan('app:close-resolved-tickets')
            ->expectsOutput('Closed 1 resolved tickets.')
            ->assertExitCode(0);

        $ticket->refresh();

        $this->assertSame($closed->id, $ticket->status_id);
        $this->assertNotNull($ticket->resolved_at);
        $this->assertNotNull($ticket->closed_at);
    }

    public function test_command_does_not_close_tickets_resolved_less_than_seven_days(): void
    {
        $resolved = $this->createStatus('Resolved', '#10B981', 4);
        $this->createStatus('Closed', '#6B7280', 5);

        $ticket = Ticket::factory()->create([
            'status_id' => $resolved->id,
            'resolved_at' => now()->subDays(3),
        ]);

        $this->artisan('app:close-resolved-tickets')
            ->expectsOutput('Closed 0 resolved tickets.')
            ->assertExitCode(0);

        $ticket->refresh();

        $this->assertSame($resolved->id, $ticket->status_id);
        $this->assertNull($ticket->closed_at);
        $this->assertNotNull($ticket->resolved_at);
    }

    public function test_command_does_not_close_tickets_without_a_resolution_date(): void
    {
        $resolved = $this->createStatus('Resolved', '#10B981', 4);
        $this->createStatus('Closed', '#6B7280', 5);

        $ticket = Ticket::factory()->create([
            'status_id' => $resolved->id,
            'resolved_at' => null,
        ]);

        $this->artisan('app:close-resolved-tickets')
            ->expectsOutput('Closed 0 resolved tickets.')
            ->assertExitCode(0);

        $ticket->refresh();

        $this->assertSame($resolved->id, $ticket->status_id);
        $this->assertNull($ticket->closed_at);
        $this->assertNull($ticket->resolved_at);
    }

    private function createStatus(string $name, string $color, int $sortOrder): TicketStatus
    {
        return TicketStatus::create([
            'name' => $name,
            'color' => $color,
            'sort_order' => $sortOrder,
        ]);
    }
}
