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
        $open = TicketStatus::where('name', 'Open')->first();
        $assigned = TicketStatus::where('name', 'Assigned')->first();
        $resolved = TicketStatus::where('name', 'Resolved')->first();

        // Prioridades
        $high = Priority::where('name', 'High')->first();
        $medium = Priority::where('name', 'Medium')->first();
        $low = Priority::where('name', 'Low')->first();

        // Categorías
        $software = Category::where('name', 'Software')->first();
        $network = Category::where('name', 'Network')->first();
        $accounts = Category::where('name', 'Accounts')->firstOrFail();

        // Subcategorías
        $outlook = Subcategory::where('name', 'Outlook')->first();
        $vpn = Subcategory::where('name', 'VPN')->first();
        $passwordReset = Subcategory::where('name', 'Password Reset')->first();

        // Tipo de resolución
        $manualExecution = ResolutionType::where('name', 'Manual Execution')->first();

        // Usuario
        $user = User::where('email', 'admin@ticketops.local')->first();

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
                'assigned_to' => null,
                'status_id' => $open->id,
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
                'assigned_to' => null,
                'status_id' => $open->id,
                'priority_id' => $low->id,
                'category_id' => $accounts->id,
                'subcategory_id' => $passwordReset->id,
                'resolution' => null,
                'resolution_type_id' => null,
                'resolved_at' => null,
                'closed_at' => null,
            ]
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}
