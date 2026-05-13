<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_register_pages_are_available_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Sign in to manage bookings');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Create your e-booking account');
    }

    public function test_api_registration_returns_standard_response_shape_and_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test Player',
            'email' => 'player@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role'],
                    'token',
                ],
            ])
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'user');

        $this->assertDatabaseHas('users', [
            'email' => 'player@example.com',
            'role' => 'user',
        ]);
    }

    public function test_api_login_returns_token_for_existing_user(): void
    {
        User::factory()->create([
            'name' => 'Court Owner',
            'email' => 'owner@example.com',
            'password' => 'password123',
            'role' => 'owner',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'owner@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'owner');

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_web_registration_signs_user_in_and_redirects_to_dashboard(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Mobile User',
            'email' => 'mobile@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Mobile User');
    }
}
