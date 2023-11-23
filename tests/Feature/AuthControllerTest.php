<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        // Attempt to login
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@user.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    // Add more tests for logout and token refresh
}
