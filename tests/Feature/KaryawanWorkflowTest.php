<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Inventory;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KaryawanWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $karyawan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->karyawan = User::factory()->create(['role' => 'karyawan', 'is_active' => true]);
        
        // Setup inventory for consumption tests
        Inventory::create(['id' => 1, 'item_name' => 'Sabun', 'stock' => 10, 'unit' => 'Liter']);
        Inventory::create(['id' => 2, 'item_name' => 'Pewangi', 'stock' => 10, 'unit' => 'Liter']);

        Service::create(['id' => 1, 'name' => 'Cuci Kering', 'price' => 7000]);
    }

    public function test_karyawan_can_view_dashboard_with_queues()
    {
        Transaction::factory()->create(['service_status' => 'menunggu']);
        
        $response = $this->actingAs($this->karyawan)->get('/karyawan/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('antrian');
    }

    public function test_karyawan_can_update_status_to_proses_cuci()
    {
        $transaction = Transaction::factory()->create(['service_status' => 'menunggu']);

        $response = $this->actingAs($this->karyawan)->patch("/karyawan/transactions/{$transaction->id}/status", [
            'service_status' => 'proses_cuci'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'service_status' => 'proses_cuci',
            'karyawan_id' => $this->karyawan->id
        ]);
    }

    public function test_karyawan_status_update_to_selesai_cuci_triggers_inventory_reduction()
    {
        $transaction = Transaction::factory()->create([
            'service_status' => 'proses_cuci',
            'quantity' => 10 // Should consume 1L soap (10 * 0.1) and 0.5L perfume (10 * 0.05)
        ]);

        $response = $this->actingAs($this->karyawan)->patch("/karyawan/transactions/{$transaction->id}/status", [
            'service_status' => 'selesai_cuci'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('inventories', ['id' => 1, 'stock' => 9.00]);
        $this->assertDatabaseHas('inventories', ['id' => 2, 'stock' => 9.50]);
    }

    public function test_karyawan_can_create_walkin_order()
    {
        $response = $this->actingAs($this->karyawan)->post('/karyawan/walkin', [
            'customer_name_offline' => 'Walkin Cust',
            'service_id' => 1,
            'quantity' => 2,
            'payment_method' => 'cash',
            'notes' => 'Tunggu sebentar'
        ]);

        $response->assertRedirect('/karyawan/dashboard');
        $this->assertDatabaseHas('transactions', [
            'customer_name_offline' => 'Walkin Cust',
            'total_amount' => 14000,
            'order_type' => 'offline_walkin',
            'payment_status' => 'paid'
        ]);
    }

    public function test_karyawan_can_manage_inventory()
    {
        $item = Inventory::first();

        // Update stock
        $this->actingAs($this->karyawan)->patch("/karyawan/inventaris/{$item->id}", [
            'stock' => 50
        ]);

        $this->assertDatabaseHas('inventories', ['id' => $item->id, 'stock' => 50]);

        // Delete item
        $this->actingAs($this->karyawan)->delete("/karyawan/inventaris/{$item->id}");
        $this->assertDatabaseMissing('inventories', ['id' => $item->id]);
    }
}
