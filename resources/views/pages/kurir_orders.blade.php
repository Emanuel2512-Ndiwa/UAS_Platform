{{-- resources/views/pages/kurir_orders.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pesanan yang Menggunakan Layanan Anda (Kurir)</h2>

    @if ($transactions->isEmpty())
        <div class="alert alert-info">Belum ada pesanan yang menggunakan layanan Anda.</div>
    @else
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Order ID</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Alamat</th>
                <th>Status Layanan</th>
                <th>Waktu Jemput</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $trx)
                <tr>
                    <td>{{ $trx->order_id }}</td>
                    <td>{{ $trx->user->name ?? 'N/A' }}</td>
                    <td>{{ $trx->service->name ?? 'N/A' }}</td>
                    <td>{{ $trx->address }}</td>
                    <td>{{ ucfirst($trx->service_status) }}</td>
                    <td>{{ $trx->pickup_time ?? '-' }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $trx->id) }}" class="btn btn-sm btn-primary">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
