<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /** Tampilkan daftar layanan untuk dipilih pelanggan */
    public function index()
    {
        $services = Service::all();
        return view('pelanggan.order.index', compact('services'));
    }

    /** Tampilkan form checkout untuk service tertentu */
    public function checkout(Service $service)
    {
        return view('pelanggan.order.checkout', compact('service'));
    }

    /** Buat order baru (online) */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id'      => 'required|exists:services,id',
            'order_type'      => 'required|in:online_pickup,online_delivery',
            'quantity'        => 'required|integer|min:1',
            'pickup_address'  => 'required_if:order_type,online_pickup|nullable|string|max:255',
            'delivery_address'=> 'required_if:order_type,online_delivery|nullable|string|max:255',
            'notes'           => 'nullable|string',
        ]);

        $service     = Service::findOrFail($validated['service_id']);
        $totalAmount = $service->price * $validated['quantity'];

        $transaction = Transaction::create([
            ...$validated,
            'user_id'        => Auth::id(),
            'total_amount'   => $totalAmount,
            'payment_status' => 'unpaid',
            'service_status' => 'menunggu',
        ]);

        // Langsung arahkan ke pembayaran Midtrans
        return redirect()->route('payment.checkout', $transaction->id);
    }

    /** Riwayat order pelanggan */
    public function history()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('service', 'kurir')
            ->latest()
            ->paginate(10);

        return view('pelanggan.order.history', compact('transactions'));
    }

    /** Detail order beserta status & tracking kurir */
    public function show(Transaction $transaction)
    {
        // Pastikan hanya pemilik order yang bisa lihat
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load('service', 'kurir', 'karyawan');

        // Generate link WA konfirmasi ke pelanggan (jika sudah diantar)
        $waLinkKurir = null;
        if ($transaction->kurir && $transaction->service_status === 'diantar') {
            $phone = preg_replace('/[^0-9]/', '', $transaction->kurir->phone_number);
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            }
            $pesan = urlencode("Halo, saya pelanggan order *{$transaction->order_id}*. Apakah paket saya sudah dalam perjalanan?");
            $waLinkKurir = "https://wa.me/{$phone}?text={$pesan}";
        }

        return view('pelanggan.order.show', compact('transaction', 'waLinkKurir'));
    }
}
