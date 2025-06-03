<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'done')->count();

        $recentOrders = Order::with(['user', 'kurir'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'pendingOrders',
            'completedOrders',
            'recentOrders'
        ));
    }
}
