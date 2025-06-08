<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class KurirDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with(['user', 'items']) // relasi ke user dan item laundry
            ->when($user->Role === 'Kurir', function ($query) use ($user) {
                $query->where('KurirID', $user->id);
            })
            ->latest()
            ->get();

        return view('kurir.dashboard', compact('orders'));
    }
}
