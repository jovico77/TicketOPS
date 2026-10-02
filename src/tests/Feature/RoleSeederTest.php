<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_seeder_creates_canonical_roles_idempotently(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->assertDatabaseCount('roles', 3);
        $this->assertDatabaseHas('roles', ['name' => 'Administrator']);
        $this->assertDatabaseHas('roles', ['name' => 'Technician']);
        $this->assertDatabaseHas('roles', ['name' => 'User']);
        $this->assertDatabaseMissing('roles', ['name' => 'Admin']);
    }

    public function test_seeded_administrator_user_uses_canonical_role(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);

        $administrator = User::where('email', 'admin@ticketops.local')->firstOrFail();

        $this->assertSame('Administrator', $administrator->role->name);
    }
}
