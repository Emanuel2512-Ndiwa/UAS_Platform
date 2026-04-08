<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_auto_generates_order_id()
    {
        $transaction = Transaction::create([
            'order_type' => 'offline_walkin',
            'total_amount' => 10000,
            'payment_status' => 'unpaid',
            'service_status' => 'menunggu',
        ]);

        $this->assertNotEmpty($transaction->order_id);
        $this->assertStringStartsWith('LAUNDRY-', $transaction->order_id);
    }

    public function test_transaction_does_not_overwrite_provided_order_id()
    {
        $customId = 'CUSTOM-123';
        $transaction = Transaction::create([
            'order_id' => $customId,
            'order_type' => 'offline_walkin',
            'total_amount' => 10000,
            'payment_status' => 'unpaid',
            'service_status' => 'menunggu',
        ]);

        $this->assertEquals($customId, $transaction->order_id);
    }
}
