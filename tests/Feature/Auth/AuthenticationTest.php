<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role; // Ensure Role is imported
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        // Create a specific role using the factory state
        $role = Role::factory()->churchMember()->create(); // Example: create a 'church-member' role
        // Or you could create any other role defined in your factory, e.g., systemAdmin(), ministryLeader(), etc.

        // Create a user and assign the created role's ID
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        // Create a specific role for this test as well
        $role = Role::factory()->churchMember()->create();
        $user = User::factory()->create(['role_id' => $role->id]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout()
    {
        // Create a specific role for this test
        $role = Role::factory()->churchMember()->create();
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
