<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed()
    {
        $role = Role::factory()->churchMember()->create();
        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this
            ->actingAs($user)
            ->get('/settings/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated()
    {
        $role = Role::factory()->churchMember()->create();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'username' => 'Original Name', // Changed from 'name' to 'username'
            'email' => 'original@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/settings/profile', [
                'username' => 'User', // Changed from 'name' to 'username' in the request payload
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $user->refresh();

        $this->assertSame('User', $user->username); // Changed from $user->name to $user->username
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged()
    {
        $role = Role::factory()->churchMember()->create();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/settings/profile', [
                'username' => 'Test User', // Changed from 'name' to 'username'
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account()
    {
        $role = Role::factory()->churchMember()->create();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => bcrypt('password'),
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/settings/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account()
    {
        $role = Role::factory()->churchMember()->create();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => bcrypt('password'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/settings/profile')
            ->delete('/settings/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/settings/profile');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertNotNull($user->fresh());
    }
}