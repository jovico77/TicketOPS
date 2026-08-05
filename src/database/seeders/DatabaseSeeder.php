<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            TicketStatusSeeder::class,
            PrioritySeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            ResolutionTypeSeeder::class,
            UserSeeder::class,
            TicketSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
