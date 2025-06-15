<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Service;

class DashboardController extends Controller
{
    // Dashboard untuk Admin
    public function adminDashboard()
    {
        $totalUser = \App\Models\User::count();
        $totalTransaksi = Transaction::count();
        $totalLayanan = Service::count();

        return view('dashboard.admin', compact('totalUser', 'totalTransaksi', 'totalLayanan'));
    }

    // Dashboard untuk Karyawan
    public function karyawanDashboard()
    {
        $transactions = Transaction::where('service_status', '!=', 'selesai')->latest()->get();
        return view('dashboard.karyawan', compact('transactions'));
    }

    // Dashboard untuk Kurir
    public function kurirDashboard()
    {
        $transactions = Transaction::where('kurir_id', Auth::id())->latest()->get();
        return view('dashboard.kurir', compact('transactions'));
    }

    // Dashboard untuk Pelanggan
    public function pelanggan()
    {
        $userId = Auth::id();
        $transactions = Transaction::where('user_id', $userId)->latest()->get();

        return view('dashboard.pelanggan', compact('transactions'));
    }
}
