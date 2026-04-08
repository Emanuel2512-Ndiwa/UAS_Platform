<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    /** Dashboard Karyawan */
    public function dashboard()
    {
        $antrian = Transaction::whereIn('service_status', ['menunggu', 'proses_cuci'])
            ->with('user', 'service')
            ->latest()
            ->get();

        $inventaris = Inventory::orderBy('status')->get();

        return view('dashboard.karyawan', compact('antrian', 'inventaris'));
    }

    // -------------------------------------------------------------------------
    // STATUS LAUNDRY
    // -------------------------------------------------------------------------

    /**
     * Update status service laundry
     * Alur: menunggu -> proses_cuci -> selesai_cuci
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $allowed = ['menunggu', 'proses_cuci', 'selesai_cuci'];

        $request->validate([
            'service_status' => 'required|in:' . implode(',', $allowed),
        ]);

        $transaction->update([
            'service_status' => $request->service_status,
            'karyawan_id'    => Auth::id(),
        ]);

        // Jika selesai cuci, potong stok inventaris (sabun/pewangi)
        if ($request->service_status === 'selesai_cuci') {
            $this->consumeInventory($transaction->quantity ?? 1);
        }

        return back()->with('success', 'Status berhasil diperbarui ke: ' . $request->service_status);
    }

    /**
     * Kurangi stok inventaris berdasarkan jumlah pakaian (quantity)
     * 0.1 liter sabun & 0.05 liter pewangi per kg/item
     */
    private function consumeInventory(int $quantity): void
    {
        $konsumsiSabun    = $quantity * 0.1;
        $konsumsiPewangi  = $quantity * 0.05;

        $sabun   = Inventory::where('item_name', 'LIKE', '%sabun%')->first();
        $pewangi = Inventory::where('item_name', 'LIKE', '%pewangi%')->first();

        if ($sabun) {
            $sabun->stock = max(0, $sabun->stock - $konsumsiSabun);
            $sabun->save(); // booted() akan auto-update status
        }
        if ($pewangi) {
            $pewangi->stock = max(0, $pewangi->stock - $konsumsiPewangi);
            $pewangi->save();
        }
    }

    // -------------------------------------------------------------------------
    // WALK-IN ORDER (Input manual di toko)
    // -------------------------------------------------------------------------

    /** Form tambah order walk-in */
    public function createWalkin()
    {
        $services = Service::all();
        return view('karyawan.walkin.create', compact('services'));
    }

    /** Simpan order walk-in */
    public function storeWalkin(Request $request)
    {
        $validated = $request->validate([
            'customer_name_offline' => 'required|string|max:150',
            'service_id'            => 'required|exists:services,id',
            'quantity'              => 'required|integer|min:1',
            'payment_method'        => 'required|in:cash,transfer',
            'notes'                 => 'nullable|string',
        ]);

        $service      = Service::findOrFail($validated['service_id']);
        $totalAmount  = $service->price * $validated['quantity'];

        Transaction::create([
            ...$validated,
            'order_type'     => 'offline_walkin',
            'total_amount'   => $totalAmount,
            'payment_status' => 'paid', // Walk-in bayar di tempat
            'service_status' => 'menunggu',
            'karyawan_id'    => Auth::id(),
        ]);

        return redirect()->route('karyawan.dashboard')->with('success', 'Order walk-in berhasil dibuat.');
    }

    // -------------------------------------------------------------------------
    // MANAJEMEN INVENTARIS
    // -------------------------------------------------------------------------

    /** Daftar inventaris */
    public function inventaris()
    {
        $items = Inventory::orderBy('status')->get();
        return view('karyawan.inventaris.index', compact('items'));
    }

    /** Form tambah item inventaris */
    public function createInventaris()
    {
        return view('karyawan.inventaris.create');
    }

    /** Simpan item inventaris baru */
    public function storeInventaris(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:100',
            'stock'     => 'required|numeric|min:0',
            'unit'      => 'required|string|max:20',
        ]);

        Inventory::create($validated);

        return redirect()->route('karyawan.inventaris')->with('success', 'Item berhasil ditambahkan.');
    }

    /** Update stok inventaris */
    public function updateInventaris(Request $request, Inventory $inventory)
    {
        $request->validate(['stock' => 'required|numeric|min:0']);

        $inventory->stock = $request->stock;
        $inventory->save(); // booted() akan update status otomatis

        return back()->with('success', "Stok {$inventory->item_name} berhasil diperbarui.");
    }

    /** Hapus item inventaris */
    public function destroyInventaris(Inventory $inventory)
    {
        $inventory->delete();
        return back()->with('success', 'Item dihapus.');
    }
}
