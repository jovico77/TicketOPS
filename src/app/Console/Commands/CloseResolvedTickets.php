<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:close-resolved-tickets')]
#[Description('Close tickets resolved for at least seven days')]
class CloseResolvedTickets extends Command
{
    public function handle(): int
    {
        $resolvedStatus = TicketStatus::where('name', 'Resolved')->firstOrFail();
        $closedStatus = TicketStatus::where('name', 'Closed')->firstOrFail();

        $tickets = Ticket::where('status_id', $resolvedStatus->id)
            ->whereNotNull('resolved_at')
            ->where('resolved_at', '<=', now()->subDays(7))
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->update([
                'status_id' => $closedStatus->id,
                'closed_at' => now(),
            ]);
        }

        $this->info("Closed {$tickets->count()} resolved tickets.");

        return self::SUCCESS;
    }
}
