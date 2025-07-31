<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role; // Add this line
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        // Ensure you have a 'Role' factory set up with a 'churchMember' state,
        // or adjust this line based on your actual Role factory and states.
        $role = Role::factory()->churchMember()->create();
        $this->actingAs(User::factory()->create(['role_id' => $role->id]));        

        $this->get('/dashboard')->assertOk();
    }
}