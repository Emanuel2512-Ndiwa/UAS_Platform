<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    public function test_admin_can_view_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard.admin');
    }

    public function test_admin_can_create_new_staff()
    {
        $userData = [
            'firstname' => 'New',
            'lastname' => 'Karyawan',
            'email' => 'staff@test.com',
            'phone_number' => '08123456789',
            'address' => 'Jakarta',
            'role' => 'karyawan',
            'password' => 'password123',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/users', $userData);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', ['email' => 'staff@test.com', 'role' => 'karyawan']);
    }

    public function test_admin_can_toggle_user_status()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($this->admin)->patch("/admin/users/{$user->id}/toggle");

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => false]);

        // Toggle back
        $this->actingAs($this->admin)->patch("/admin/users/{$user->id}/toggle");
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => true]);
    }

    public function test_admin_can_cancel_transaction()
    {
        $transaction = Transaction::factory()->create(['service_status' => 'menunggu']);

        $response = $this->actingAs($this->admin)->patch("/admin/transactions/{$transaction->id}/cancel");

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'service_status' => 'selesai_total',
            'payment_status' => 'cancel'
        ]);
    }

    public function test_admin_can_generate_wa_link()
    {
        $karyawan = User::factory()->create(['role' => 'karyawan', 'phone_number' => '08123456789']);
        $transaction = Transaction::factory()->create([
            'karyawan_id' => $karyawan->id,
            'service_status' => 'menunggu',
            'customer_name_offline' => 'Budi'
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/transactions/{$transaction->id}/wa");

        $response->assertRedirect();
        $this->assertStringContainsString('wa.me/628123456789', $response->headers->get('Location'));
    }
}
