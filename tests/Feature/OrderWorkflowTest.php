<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $pelanggan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pelanggan = User::factory()->create(['role' => 'pelanggan', 'is_active' => true]);
        Service::create(['id' => 1, 'name' => 'Cuci Kering', 'price' => 7000]);
    }

    public function test_pelanggan_can_view_services_list()
    {
        $response = $this->actingAs($this->pelanggan)->get('/order');
        $response->assertStatus(200);
        $response->assertViewHas('services');
    }

    public function test_pelanggan_can_create_online_order()
    {
        $response = $this->actingAs($this->pelanggan)->post('/order', [
            'service_id' => 1,
            'order_type' => 'online_pickup',
            'quantity' => 1,
            'pickup_address' => 'My Home',
            'notes' => 'Tolong hati-hati'
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->pelanggan->id,
            'order_type' => 'online_pickup',
            'total_amount' => 7000,
            'payment_status' => 'unpaid'
        ]);

        $transaction = Transaction::where('user_id', $this->pelanggan->id)->first();
        $response->assertRedirect("/payment/{$transaction->id}");
    }

    public function test_pelanggan_can_view_order_history()
    {
        Transaction::factory()->create(['user_id' => $this->pelanggan->id]);

        $response = $this->actingAs($this->pelanggan)->get('/order/history');

        $response->assertStatus(200);
        $response->assertViewHas('transactions');
    }

    public function test_pelanggan_can_view_order_detail()
    {
        $transaction = Transaction::factory()->create(['user_id' => $this->pelanggan->id]);

        $response = $this->actingAs($this->pelanggan)->get("/order/{$transaction->id}");

        $response->assertStatus(200);
        $response->assertViewIs('pelanggan.order.show');
    }

    public function test_pelanggan_cannot_view_others_order()
    {
        $otherUser = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->pelanggan)->get("/order/{$transaction->id}");

        $response->assertStatus(403);
    }
}
