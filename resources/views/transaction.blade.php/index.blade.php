@section('content')
<div class="container">
    <h2>Daftar Transaksi Saya</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Layanan</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Status Layanan</th>
                <th>Status Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $trx)
                <tr>
                    <td>{{ $trx->order_id }}</td>
                    <td>{{ $trx->service->name ?? '-' }}</td>
                    <td>{{ $trx->quantity }}</td>
                    <td>Rp{{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($trx->service_status) }}</td>
                    <td>{{ ucfirst($trx->payment_status) }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $trx->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <form method="POST" action="{{ route('transactions.delete', $trx->id) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>