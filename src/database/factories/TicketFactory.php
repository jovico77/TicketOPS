<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'ticket_number' => 'TCK-TEST-' . fake()->unique()->numerify('######'),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'created_by' => fn () => User::factory()->create([
                'role_id' => Role::firstOrCreate(['name' => 'User'])->id,
            ])->id,
            'assigned_to' => null,
            'status_id' => fn () => TicketStatus::firstOrCreate(
                ['name' => 'Open'],
                ['color' => '#2fd9eb', 'sort_order' => 1]
            )->id,
            'priority_id' => fn () => Priority::firstOrCreate(
                ['name' => 'Medium'],
                ['color' => '#F59E0B']
            )->id,
            'category_id' => fn () => Category::firstOrCreate(
                ['name' => 'Software'],
                ['description' => 'Test category']
            )->id,
            'subcategory_id' => null,
            'resolution' => null,
            'resolution_type_id' => null,
            'resolved_at' => null,
            'closed_at' => null,
        ];
    }
}
