<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\Priority;
use App\Models\ResolutionType;
use App\Models\Subcategory;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Estados
        $open = TicketStatus::where('name', 'Open')->firstorFail();
        $inProgress = TicketStatus::where('name', 'In Progress')->firstorFail();
        $resolved = TicketStatus::where('name', 'Resolved')->firstorFail();

        // Prioridades
        $high = Priority::where('name', 'High')->firstorFail();
        $medium = Priority::where('name', 'Medium')->firstorFail();
        $low = Priority::where('name', 'Low')->firstorFail();

        // Categorías
        $software = Category::where('name', 'Software')->firstorFail();
        $network = Category::where('name', 'Network')->firstorFail();
        $accounts = Category::where('name', 'Accounts')->firstorFail();

        // Subcategorías
        $outlook = Subcategory::where('name', 'Outlook')->firstorFail();
        $vpn = Subcategory::where('name', 'VPN')->firstorFail();
        $passwordReset = Subcategory::where('name', 'Password Reset')->firstorFail();

        // Tipo de resolución
        $manualExecution = ResolutionType::where('name', 'Manual Execution')->firstorFail();

        // Usuario
        $user = User::where('email', 'admin@ticketops.local')->firstorFail();

        // Crear array de tickets
        $tickets = [
            [
                'ticket_number' => 'TCK-2026-000001',
                'title' => 'Unable to access Outlook',
                'description' => 'The user cannot open Outlook. The application closes immediately after launch.',
                'created_by' => $user->id,
                'assigned_to' => null,
                'status_id' => $open->id,
                'priority_id' => $high->id,
                'category_id' => $software->id,
                'subcategory_id' => $outlook->id,
                'resolution' => null,
                'resolution_type_id' => null,
                'resolved_at' => null,
                'closed_at' => null,
            ],
            [
                'ticket_number' => 'TCK-2026-000002',
                'title' => 'VPN connection issues',
                'description' => 'The user cannot connect to the VPN. It shows an authentication error.',
                'created_by' => $user->id,
                'assigned_to' => $user->id,
                'status_id' => $inProgress->id,
                'priority_id' => $medium->id,
                'category_id' => $network->id,
                'subcategory_id' => $vpn->id,
                'resolution' => null,
                'resolution_type_id' => null,
                'resolved_at' => null,
                'closed_at' => null,
            ],
            [
                'ticket_number' => 'TCK-2026-000003',
                'title' => 'Password reset request',
                'description' => 'The user forgot their password and needs a reset.',
                'created_by' => $user->id,
                'assigned_to' => $user->id,
                'status_id' => $resolved->id,
                'priority_id' => $low->id,
                'category_id' => $accounts->id,
                'subcategory_id' => $passwordReset->id,
                'resolution' => 'Password reset completed. User can now log in with the new password.',
                'resolution_type_id' => $manualExecution->id,
                'resolved_at' => now(),
                'closed_at' => null,
            ]
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}
