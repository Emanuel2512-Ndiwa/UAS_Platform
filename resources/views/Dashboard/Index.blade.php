@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <h3>{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5>Total Revenue</h5>
                    <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <h5>Pending Orders</h5>
                    <h3>{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mt-5">Recent Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Kurir</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recentOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ $order->kurir->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ $order->order_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
