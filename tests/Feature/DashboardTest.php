<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['id' => 1], ['role_name' => 'Test Role']);
    }

    /**
     * Authenticated users can visit the dashboard.
     *
     * @return void
     */
    public function test_authenticated_users_can_visit_the_dashboard()
    {
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)
                         ->get('/dashboard');

        $response->assertStatus(200);

        // --- IMPORTANT: Change your assertion for Inertia.js applications ---
        // Assert that the Inertia component being rendered is 'dashboard'
        $response->assertInertia(fn (
            \Inertia\Testing\AssertableInertia $page
        ) => $page->component('dashboard'));

        // You can also assert on props if needed, for example,
        // if your dashboard component receives a 'name' prop:
        // ->where('name', 'NLOT Church Management System')
        // Or specific user data if it's passed as a prop:
        // ->has('auth.user', fn (AssertableInertia $user) =>
        //     $user->where('username', $user->username)
        //          ->etc()
        // )
    }
}