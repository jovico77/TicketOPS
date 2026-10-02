<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_accessible_credentials_form(): void
    {
        $response = $this->get(route('login'));

        $response
            ->assertOk()
            ->assertSee('TicketOPS')
            ->assertSee('Sign in to your account')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('action="' . route('login') . '"', false)
            ->assertSee('href="' . route('register') . '"', false)
            ->assertSeeInOrder([
                'class="login-footer"',
                "Don't have an account?",
                '&copy;',
                'TicketOPS',
            ], false)
            ->assertSee('Google sign-in (not configured)', false)
            ->assertSee('Apple sign-in (not configured)', false)
            ->assertSee('GitHub sign-in (not configured)', false);
    }

    public function test_register_page_renders_registration_form(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Create your account')
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('action="' . route('register.store') . '"', false);
    }

    public function test_new_registrations_are_created_as_users_and_signed_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Taylor Example',
            'email' => 'taylor@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ]);

        $response->assertRedirect(route('tickets.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('roles', ['name' => 'User']);
        $this->assertDatabaseHas('users', [
            'name' => 'Taylor Example',
            'email' => 'taylor@example.com',
        ]);
    }

    public function test_registration_cannot_assign_a_privileged_role(): void
    {
        $administratorRole = Role::firstOrCreate(['name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'Technician']);

        $response = $this->post(route('register.store'), [
            'name' => 'Morgan Example',
            'email' => 'morgan@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
            'role_id' => $administratorRole->id,
        ]);

        $response->assertRedirect(route('tickets.index'));

        $registeredUser = User::where('email', 'morgan@example.com')->firstOrFail();

        $this->assertSame('User', $registeredUser->role->name);
    }
}
