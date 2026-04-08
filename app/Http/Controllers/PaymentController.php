<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Buat Snap Token Midtrans dan simpan ke transaksi.
     * Mengembalikan JSON { snap_token, order_id }
     */
    public function createSnapToken(Transaction $transaction)
    {
        // Pastikan hanya pemilik order yang bisa request token
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $user = $transaction->user;

        $params = [
            'transaction_details' => [
                'order_id'     => $transaction->order_id,
                'gross_amount' => (int) $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user->firstname,
                'last_name'  => $user->lastname,
                'email'      => $user->email,
                'phone'      => $user->phone_number,
            ],
            'enabled_payments' => ['gopay', 'qris', 'bca_va', 'bni_va', 'bri_va'],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['midtrans_snap_token' => $snapToken]);

            return response()->json(['snap_token' => $snapToken, 'order_id' => $transaction->order_id]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat sesi pembayaran.'], 500);
        }
    }

    /** Halaman checkout (view yang memuat Snap JS) */
    public function checkoutPage(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        $transaction->load('service');
        return view('payment.checkout', compact('transaction'));
    }

    /**
     * Webhook Midtrans — dipanggil server Midtrans setelah pembayaran berhasil.
     * Route ini HARUS dikecualikan dari CSRF verification.
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        // Verifikasi signature key dari Midtrans
        $signatureKey = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('midtrans.server_key')
        );

        if ($signatureKey !== $payload['signature_key']) {
            Log::warning('Midtrans webhook signature tidak valid.', $payload);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('order_id', $payload['order_id'])->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $paymentStatus = match ($payload['transaction_status']) {
            'capture', 'settlement' => 'paid',
            'expire'                => 'expire',
            'cancel', 'deny'        => 'cancel',
            default                 => $transaction->payment_status,
        };

        $transaction->update([
            'payment_status' => $paymentStatus,
            'midtrans_id'    => $payload['transaction_id'] ?? $transaction->midtrans_id,
        ]);

        Log::info("Midtrans webhook processed: Order {$transaction->order_id} -> {$paymentStatus}");

        return response()->json(['message' => 'OK']);
    }
}
