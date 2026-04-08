<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_with_correct_credentials_can_login()
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login()
    {
        $user = User::factory()->create([
            'email' => 'inactive@test.com',
            'password' => Hash::make('password'),
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@test.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_register_as_pelanggan()
    {
        $response = $this->post('/register', [
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'new@test.com',
            'phone_number' => '08123',
            'address' => 'Test Address',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/order/history');
        $this->assertDatabaseHas('users', ['email' => 'new@test.com', 'role' => 'pelanggan']);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
