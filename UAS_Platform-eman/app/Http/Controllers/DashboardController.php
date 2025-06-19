<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Service;

class DashboardController extends Controller
{
   
   

    // Dashboard untuk Karyawan
    public function karyawanDashboard()
    {
        $transactions = Transaction::where('service_status', '!=', 'selesai')->latest()->get();
        return view('dashboard.karyawan', compact('transactions'));
    }

    // Dashboard untuk Kurir
    public function kurirDashboard()
    {
        $transactions = Transaction::with(['user', 'service'])
            ->where('service_status', 'done') // status sudah selesai dicuci
            ->whereNull('delivery_time')      // belum dikirim
            ->latest()
            ->get();

        return view('dashboard.kurir', compact('transactions'));
    }
    public function pelanggan()
    {
        $services = Service::all();
        $transactions = Transaction::where('user_id', auth()->id())->latest()->get();

        return view('dashboard.pelanggan', compact('services', 'transactions'));
    }
}