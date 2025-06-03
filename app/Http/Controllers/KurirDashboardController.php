<?php 
// app/Http/Controllers/KurirDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class KurirDashboardController extends Controller
{
    public function index()
    {
        $kurirID = Auth::id(); // ID kurir yang login
        $orders = Order::with('user')->where('KurirID', $kurirID)->get();

        return view('kurir.dashboard', compact('orders'));
    }
}
