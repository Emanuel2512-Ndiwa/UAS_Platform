<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan daftar transaksi pengguna
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())->get();
        return view('transaction.index', compact('transactions'));
    }

    // Membuat transaksi baru
    public function create_transaction(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'status' => 'required|string|in:pending,completed,canceled'
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'amount' => $validatedData['amount'],
            'status' => $validatedData['status']
        ]);

        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil dibuat.');
    }

    // Memperbarui status transaksi
    public function update_status(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $request->validate([
            'status' => 'required|string|in:pending,completed,canceled'
        ]);

        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('transaction.index')->with('success', 'Status transaksi diperbarui.');
    }
    public function store(Request $request)
    {

        // dd($request->all());
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'notes' => 'nullable|string',
            'pickup_time' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'payment_status' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'service_status' => 'nullable|string|max:255',
            'address' => 'required|string|max:1000',
        ]);

        try {
            $pickupTime = null;
            if (!empty($validated['pickup_time'])) {
                $parts = explode('-', $validated['pickup_time']);
                if (count($parts) > 0) {
                    $pickupTime = now()->setTimeFromTimeString(trim($parts[0]));
                }
            }


            Transaction::create([
                'order_id' => $this->generateOrderId(),
                'user_id' => auth()->id(),
                'service_id' => $validated['service_id'],
                'payment_status' => $validated['payment_status'] ?? 'menunggu',
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'service_status' => $validated['service_status'] ?? 'menunggu',
                'amount' => $request->amount, // pastikan ini ADA

                'quantity' => $validated['quantity'] ?? 0,
                'address' => $validated['address'],
                'notes' => $validated['notes'] ?? null,
                'pickup_time' => $pickupTime,
            ]);

            return redirect()->route('order')->with('success', 'Transaksi berhasil dibuat!');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function kurirOrders()
    {
        $userId = auth()->id();

        // Ambil transaksi yang layanannya pakai kurir ini (misal relasi service punya atribut kurir_id atau sesuai logika)
        // Asumsi: ada kolom kurir_id di transaksi atau service_id yang dapat dilink ke kurir

        $transactions = Transaction::with(['user', 'service'])
            ->where('kurir_id', $userId) // sesuaikan dengan struktur db kamu
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.kurir_orders', compact('transactions'));
    }

    public function show(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('pages.order_detail', compact('transaction'));
    }

    // Menghapus transaksi jika diperlukan
    public function delete_transaction($id)
    {
        Transaction::findOrFail($id)->delete();
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
