<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class KurirController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:kurir']);
    }

    public function index()
    {
        return view('kurir.dashboard');
    }

    public function orders()
    {
        $orders = Order::where('status', 'in_transit')->get();
        return view('kurir.orders', compact('orders'));
    }

    public function update_order_status(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('kurir.orders')->with('success', 'Status pesanan diperbarui.');
    }
}