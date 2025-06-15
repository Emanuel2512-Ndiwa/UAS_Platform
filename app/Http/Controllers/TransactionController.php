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

    // Menghapus transaksi jika diperlukan
    public function delete_transaction($id)
    {
        Transaction::findOrFail($id)->delete();
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}