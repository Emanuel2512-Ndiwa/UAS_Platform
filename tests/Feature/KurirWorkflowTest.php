<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KurirWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $kurir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->kurir = User::factory()->create(['role' => 'kurir', 'is_active' => true]);
    }

    public function test_kurir_can_view_assigned_orders()
    {
        Transaction::factory()->create(['kurir_id' => $this->kurir->id]);

        $response = $this->actingAs($this->kurir)->get('/kurir/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    public function test_kurir_can_pick_up_ready_order()
    {
        $transaction = Transaction::factory()->create(['service_status' => 'selesai_cuci']);

        $response = $this->actingAs($this->kurir)->patch("/kurir/orders/{$transaction->id}/ambil");

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'service_status' => 'diantar',
            'kurir_id' => $this->kurir->id
        ]);
    }

    public function test_kurir_can_complete_delivery()
    {
        $transaction = Transaction::factory()->create([
            'kurir_id' => $this->kurir->id,
            'service_status' => 'diantar'
        ]);

        $response = $this->actingAs($this->kurir)->patch("/kurir/orders/{$transaction->id}/selesai");

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'service_status' => 'selesai_total'
        ]);
    }

    public function test_kurir_can_update_location_and_calculate_eta()
    {
        $pelanggan = User::factory()->create([
            'latitude' => -6.192435, 
            'longitude' => 106.822765 
        ]);
        
        $transaction = Transaction::factory()->create([
            'user_id' => $pelanggan->id,
            'kurir_id' => $this->kurir->id,
            'service_status' => 'diantar'
        ]);

        // Kurir is at Monas
        $response = $this->actingAs($this->kurir)->put("/kurir/location", [
            'latitude' => -6.175392,
            'longitude' => 106.827153
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['distance_km', 'eta_minutes']);
        
        $this->assertDatabaseHas('users', [
            'id' => $this->kurir->id,
            'latitude' => -6.175392
        ]);
        
        $this->assertNotNull($transaction->fresh()->distance);
    }
}
