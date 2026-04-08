<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    // -------------------------------------------------------------------------
    // DASHBOARD
    // -------------------------------------------------------------------------

    /** Dashboard utama Admin */
    public function dashboard()
    {
        $totalUser      = User::where('role', 'pelanggan')->count();
        $totalKaryawan  = User::where('role', 'karyawan')->count();
        $totalKurir     = User::where('role', 'kurir')->count();
        $totalTransaksi = Transaction::count();

        // Transaksi berstatus 'menunggu' lebih dari 12 jam
        $pending = Transaction::where('service_status', 'menunggu')
            ->where('created_at', '<=', Carbon::now()->subHours(12))
            ->with(['user', 'karyawan'])
            ->latest()
            ->get();

        // Inventaris yang stoknya kritis
        $criticalInventory = Inventory::whereIn('status', ['low_stock', 'empty'])->get();

        return view('dashboard.admin', compact(
            'totalUser', 'totalKaryawan', 'totalKurir', 'totalTransaksi',
            'pending', 'criticalInventory'
        ));
    }

    // -------------------------------------------------------------------------
    // MANAJEMEN PENGGUNA
    // -------------------------------------------------------------------------

    /** Daftar semua pengguna (kecuali admin sendiri) */
    public function users(Request $request)
    {
        $role  = $request->query('role');
        $users = User::when($role, fn($q) => $q->where('role', $role))
                     ->latest()
                     ->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /** Form tambah karyawan/kurir oleh Admin */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /** Simpan karyawan/kurir baru */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'firstname'    => 'required|string|max:100',
            'lastname'     => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'address'      => 'required|string|max:255',
            'role'         => 'required|in:karyawan,kurir',
            'password'     => 'required|string|min:8',
        ]);

        User::create([
            ...$validated,
            'password'  => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /** Toggle status aktif/suspend pengguna */
    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->firstname} berhasil {$status}.");
    }

    /** Hapus pengguna */
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil dihapus.');
    }

    // -------------------------------------------------------------------------
    // MANAJEMEN TRANSAKSI
    // -------------------------------------------------------------------------

    /** Daftar semua transaksi */
    public function transactions()
    {
        $transactions = Transaction::with(['user', 'service', 'kurir', 'karyawan'])
            ->latest()
            ->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }

    /** Batalkan transaksi */
    public function cancelTransaction(Transaction $transaction)
    {
        $transaction->update([
            'service_status' => 'selesai_total',
            'payment_status' => 'cancel',
        ]);
        return back()->with('success', 'Transaksi berhasil dibatalkan.');
    }

    // -------------------------------------------------------------------------
    // NOTIFIKASI WHATSAPP
    // -------------------------------------------------------------------------

    /**
     * Generate link WhatsApp untuk menghubungi karyawan/kurir
     * terkait transaksi yang menunggu >12 jam.
     */
    public function generateWaLink(Transaction $transaction)
    {
        $target = $transaction->karyawan ?? $transaction->kurir;
        if (!$target || !$target->phone_number) {
            return back()->with('error', 'Nomor HP petugas tidak tersedia.');
        }

        $orderId  = $transaction->order_id;
        $custName = $transaction->user?->firstname ?? $transaction->customer_name_offline ?? 'Pelanggan';
        $durasi   = Carbon::parse($transaction->created_at)->diffForHumans();

        $pesan = urlencode(
            "Halo {$target->firstname}, mohon segera proses Order *{$orderId}* " .
            "atas nama *{$custName}* yang sudah menunggu sejak {$durasi}. Terima kasih."
        );

        $phone = preg_replace('/[^0-9]/', '', $target->phone_number);
        // Ubah awalan 0 jadi 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $waUrl = "https://wa.me/{$phone}?text={$pesan}";

        return redirect()->away($waUrl);
    }
}
