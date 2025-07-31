<?php

namespace Tests\Feature\Auth;

use App\Models\Role; // Assuming you have a Role model
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $this->seed(\Database\Seeders\RolesSeeder::class); // Assuming you have a RoleSeeder

        // Or if you only need a default role for the test
        $role = Role::factory()->create(['role_name' => 'default_user_role']);

        $response = $this->post('/register', [
            'username' => 'user',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
