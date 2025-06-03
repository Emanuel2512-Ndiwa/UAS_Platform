<!-- resources/views/kurir/dashboard.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kurir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Dashboard Kurir</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID Order</th>
                <th>Nama User</th>
                <th>Pickup</th>
                <th>Delivery</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'Tidak Diketahui' }}</td>
                    <td>{{ $order->PickupTime }}</td>
                    <td>{{ $order->DeliveryTime }}</td>
                    <td>{{ $order->Status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
