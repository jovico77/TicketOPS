<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        Priority::create([
            'name' => 'Low',
            'color' => '#10B981',
        ]);

        Priority::create([
            'name' => 'Medium',
            'color' => '#F59E0B',
        ]);

        Priority::create([
            'name' => 'High',
            'color' => '#EF4444',
        ]);

        Priority::create([
            'name' => 'Critical',
            'color' => '#7C2D12',
        ]);
    }
}