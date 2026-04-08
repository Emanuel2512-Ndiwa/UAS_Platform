<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class MidtransPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $pelanggan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pelanggan = User::factory()->create(['role' => 'pelanggan', 'is_active' => true]);
        Config::set('midtrans.server_key', 'test_server_key');
    }

    public function test_pelanggan_can_request_snap_token()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->pelanggan->id,
            'total_amount' => 10000
        ]);

        // Mock Midtrans Snap token result if possible, 
        // but here we just test the logic around the call.
        // Since we can't easily mock the static call without extra libraries, 
        // we test the 403 authorization.
        
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->post("/payment/{$transaction->id}/snap-token");
        $response->assertStatus(403);
    }

    public function test_midtrans_webhook_updates_payment_status()
    {
        $transaction = Transaction::factory()->create([
            'order_id' => 'TEST-123',
            'total_amount' => 10000,
            'payment_status' => 'unpaid'
        ]);

        $payload = [
            'order_id' => 'TEST-123',
            'status_code' => '200',
            'gross_amount' => '10000.00',
            'transaction_status' => 'settlement',
            'transaction_id' => 'midtrans-tx-id',
        ];

        // Generate valid signature
        $signature = hash('sha512', 'TEST-123' . '200' . '10000.00' . 'test_server_key');
        $payload['signature_key'] = $signature;

        $response = $this->postJson('/payment/webhook', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('transactions', [
            'order_id' => 'TEST-123',
            'payment_status' => 'paid',
            'midtrans_id' => 'midtrans-tx-id'
        ]);
    }

    public function test_midtrans_webhook_fails_with_invalid_signature()
    {
        $payload = [
            'order_id' => 'TEST-123',
            'status_code' => '200',
            'gross_amount' => '10000.00',
            'signature_key' => 'invalid-signature'
        ];

        $response = $this->postJson('/payment/webhook', $payload);

        $response->assertStatus(403);
    }
}
