<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\LaundryItem;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with('items')
            ->where('UserID', $user->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pickup_time' => 'required|date',
            'items.*.ItemName' => 'required|string',
            'items.*.Weight' => 'required|numeric',
            'items.*.Price' => 'required|numeric',
        ]);

        $order = Order::create([
            'UserID' => Auth::id(),
            'Status' => 'Menunggu',
            'PickupTime' => $request->pickup_time,
            'CreatedAt' => now(),
        ]);

        foreach ($request->items as $item) {
            LaundryItem::create([
                'OrderID' => $order->id,
                'ItemName' => $item['ItemName'],
                'Weight' => $item['Weight'],
                'Price' => $item['Price'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat.');
    }

    public function destroy($id)
    {
        $order = Order::where('UserID', Auth::id())->findOrFail($id);
        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
