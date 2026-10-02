<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_create_and_edit_a_user_role(): void
    {
        $administrator = $this->createUserWithRole('Administrator');
        $technicianRole = Role::firstOrCreate(['name' => 'Technician']);
        $this->actingAs($administrator);

        $this->get(route('users.index'))
            ->assertOk()
            ->assertSee('User management');

        $this->post(route('users.store'), [
            'name' => 'Casey Technician',
            'email' => 'casey@example.com',
            'role_id' => $technicianRole->id,
            'password' => 'temporary-password',
            'password_confirmation' => 'temporary-password',
        ])->assertRedirect(route('users.index'));

        $managedUser = User::where('email', 'casey@example.com')->firstOrFail();
        $this->assertSame('Technician', $managedUser->role->name);
        $this->assertTrue($managedUser->is_active);
        $this->assertTrue(Hash::check('temporary-password', $managedUser->password));

        $administratorRole = Role::firstOrCreate(['name' => 'Administrator']);
        $this->patch(route('users.update', $managedUser), [
            'name' => 'Casey Admin',
            'email' => 'casey.admin@example.com',
            'role_id' => $administratorRole->id,
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('users.index'));

        $this->assertSame('Administrator', $managedUser->fresh()->role->name);
    }

    public function test_administrator_can_deactivate_and_reactivate_a_user_without_deleting_history(): void
    {
        $administrator = $this->createUserWithRole('Administrator');
        $managedUser = $this->createUserWithRole('User');

        $this->actingAs($administrator)
            ->delete(route('users.destroy', $managedUser))
            ->assertRedirect(route('users.index'));

        $managedUser->refresh();
        $this->assertFalse($managedUser->is_active);
        $this->assertDatabaseHas('users', ['id' => $managedUser->id]);

        $this->get(route('users.index'))
            ->assertOk()
            ->assertSee($managedUser->name)
            ->assertSee('Inactive');

        $this->patch(route('users.activate', $managedUser))
            ->assertRedirect(route('users.index'));

        $this->assertTrue($managedUser->fresh()->is_active);
    }

    public function test_only_administrator_can_access_user_management_routes(): void
    {
        $userToManage = $this->createUserWithRole('User');

        foreach (['User', 'Technician'] as $roleName) {
            $actor = $this->createUserWithRole($roleName);

            $this->actingAs($actor)
                ->get(route('users.index'))
                ->assertForbidden();

            $this->post(route('users.store'), [])->assertForbidden();
            $this->patch(route('users.update', $userToManage), [])->assertForbidden();
            $this->delete(route('users.destroy', $userToManage))->assertForbidden();
        }
    }

    public function test_last_active_administrator_cannot_be_deactivated_or_demoted(): void
    {
        $administrator = $this->createUserWithRole('Administrator');
        $this->actingAs($administrator);

        $this->delete(route('users.destroy', $administrator))
            ->assertForbidden();

        $userRole = Role::firstOrCreate(['name' => 'User']);
        $this->patch(route('users.update', $administrator), [
            'name' => $administrator->name,
            'email' => $administrator->email,
            'role_id' => $userRole->id,
            'password' => '',
            'password_confirmation' => '',
        ])->assertSessionHasErrors('role_id');

        $this->assertTrue($administrator->fresh()->is_active);
        $this->assertSame('Administrator', $administrator->fresh()->role->name);
    }

    public function test_deactivated_user_session_is_logged_out_on_next_request(): void
    {
        $user = $this->createUserWithRole('User');
        $user->update(['is_active' => false]);

        $this->actingAs($user)
            ->get(route('tickets.index'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    private function createUserWithRole(string $roleName): User
    {
        return User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => $roleName])->id,
        ]);
    }
}
