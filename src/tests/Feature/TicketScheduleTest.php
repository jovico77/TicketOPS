<?php

namespace Tests\Feature;

use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

class TicketScheduleTest extends TestCase
{
    public function test_resolved_ticket_closure_is_scheduled_daily(): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn ($event) => str_contains($event->command, 'app:close-resolved-tickets'));

        $this->assertNotNull($event);
        $this->assertSame('0 0 * * *', $event->getExpression());
    }
}
