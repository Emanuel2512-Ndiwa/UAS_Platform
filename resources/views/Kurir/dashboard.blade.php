<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kurir - Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 60px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-weight: 700;
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.5em;
        }
        ul {
            padding-left: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-4">
        <h2 class="mb-4 text-primary">Dashboard Kurir</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID Order</th>
                        <th>Nama User</th>
                        <th>Pickup</th>
                        <th>Delivery</th>
                        <th>Status</th>
                        <th>Item Laundry</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->Nama ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $order->PickupTime ?? '-' }}</td>
                            <td>{{ $order->DeliveryTime ?? '-' }}</td>
                            <td>
                                <span class="badge
                                    @if($order->Status == 'DiJemput') bg-warning
                                    @elseif($order->Status == 'Diproses') bg-primary
                                    @elseif($order->Status == 'Selesai') bg-success
                                    @else bg-secondary @endif">
                                    {{ $order->Status }}
                                </span>
                            </td>
                            <td>
                                <ul class="mb-0">
                                    @foreach($order->items as $item)
                                        <li>{{ $item->ItemName }} ({{ $item->Weight }} kg)</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                Rp{{ number_format($order->items->sum('Price'), 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
