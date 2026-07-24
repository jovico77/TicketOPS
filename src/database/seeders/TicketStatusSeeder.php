<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    public function run(): void
    {
        TicketStatus::create([
            'name' => 'Open',
            'color' => '#2fd9eb',
            'sort_order' => 1,
        ]);

        TicketStatus::create([
            'name' => 'In Progress',
            'color' => '#F59E0B',
            'sort_order' => 2,
        ]);

        TicketStatus::create([
            'name' => 'Pending',
            'color' => '#8B5CF6',
            'sort_order' => 3,
        ]);

        TicketStatus::create([
            'name' => 'Resolved',
            'color' => '#10B981',
            'sort_order' => 4,
        ]);

        TicketStatus::create([
            'name' => 'Closed',
            'color' => '#6B7280',
            'sort_order' => 5,
        ]);

        TicketStatus::create([
            'name' => 'Reopened',
            'color' => '#EF4444',
            'sort_order' => 6,
        ]);
    }
}
