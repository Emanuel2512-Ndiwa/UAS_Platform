<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - WashWuzz</title>
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/logo.png') }}">

</head>

<body>
    <!-- Header -->
    @include('components.navbar');

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Daftar Pesanan Anda</h1>
            <p>Kelola dan pantau status semua pesanan laundry Anda di satu tempat. Lihat riwayat pesanan, status
                pengerjaan, dan detail lengkap setiap pesanan.</p>
        </div>
    </section>

    <!-- Order List Section -->
    <section class="order-list-section">
        <div class="container">
            <h2 class="section-title">Pesanan Saya</h2>

            <form method="GET" action="{{ route('order') }}" class="order-filters" style="display: flex; gap: 1rem;">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Cari nomor pesanan..."
                        value="{{ request('search') }}">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>

                <div class="filter-dropdown">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                        </option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                        </option>
                        <option value="terkirim" {{ request('status') == 'terkirim' ? 'selected' : '' }}>Terkirim
                        </option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>

                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
            </form>


            <!-- Orders Table -->
            <table class="order-table">
                <thead>
                    <tr>
                        <th style="text-align: center">No. Pesanan</th>
                        <th style="text-align: center">Tanggal</th>
                        <th style="text-align: center">Layanan</th>
                        <th style="text-align: center">Berat</th>
                        <th style="text-align: center">Total</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($transactions as $transaction)
                        <tr>
                            <td style="text-align: center">{{ $transaction->order_id }}</td>
                            <td style="text-align: center">{{ $transaction->created_at }}</td>
                            <td style="text-align: center">{{ $transaction->service->name }}</td>
                            <td style="text-align: center">
                                {{ $transaction->quantity !== null && $transaction->quantity !== 0 ? $transaction->quantity . ' kg' : '-' }}
                            </td>
                            <td style="text-align: center">
                                {{ $transaction->quantity !== null && $transaction->quantity !== 0 ? 'Rp' . number_format($transaction->quantity * $transaction->service->price, 
                                0, ',', '.') : '-' }}
                            </td>
                            <td style="text-align: center">
                                <span class="status-badge status-{{ $transaction->service_status ?? 'unknown' }}">
                                    {{ $transaction->service_status ?? 'Unknown' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons" style="display: flex; justify-content: center; gap: 0.5rem;">
                                    <button class="btn"
                                        onclick="window.location.href = '/order/detail/{{ $transaction->id }}'">
                                        Lihat Detail
                                    </button>

                                    @if ($transaction->payment_status != 'sukses' && $transaction->quantity > 0 && $transaction->service_status != 'dibatalkan')
                                        <button class="btn btn-outline"
                                            onclick="window.location.href = '{{ route('order.pay', $transaction->id) }}'">
                                            Bayar
                                        </button>
                                    @endif
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">Belum ada pesanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Order Kosong -->
            <div class="no-orders" style="display: none;">
                <h3>Belum Ada Pesanan</h3>
                <p>Anda belum memiliki pesanan laundry. Mulai pesanan pertama Anda sekarang untuk menikmati layanan
                    laundry berkualitas dari WashWuzz!</p>
                <a href="{{ route('service') }}" class="cta-button">Pesan Sekarang</a>
            </div>

            <!-- Pagination -->
            @php
                $currentPage = $transactions->currentPage();
                $lastPage = $transactions->lastPage();
            @endphp

            <div class="pagination" style="display: flex; gap: 0.5rem; justify-content: center; margin-top: 2rem;">
                {{-- Tombol Sebelumnya --}}
                @if ($currentPage > 1)
                    <a href="{{ $transactions->url($currentPage - 1) }}">
                        <button>⟨</button>
                    </a>
                @else
                    <button disabled style="opacity: 0.5; cursor: not-allowed;">⟨</button>
                @endif

                {{-- Nomor Halaman --}}
                @for ($i = 1; $i <= $lastPage; $i++)
                    <a href="{{ $transactions->url($i) }}">
                        <button class="{{ $i === $currentPage ? 'active' : '' }}">
                            {{ $i }}
                        </button>
                    </a>
                @endfor

                {{-- Tombol Berikutnya --}}
                @if ($currentPage < $lastPage)
                    <a href="{{ $transactions->url($currentPage + 1) }}">
                        <button>⟩</button>
                    </a>
                @else
                    <button disabled style="opacity: 0.5; cursor: not-allowed;">⟩</button>
                @endif
            </div>
        </div>
    </section>

    @include('components.footer')

    <script>
        // Toggle menu untuk tampilan mobile
        const menuToggle = document.getElementById('menuToggle');
        const mainNav = document.getElementById('mainNav');

        menuToggle.addEventListener('click', () => {
            mainNav.classList.toggle('active');
        });
    </script>
</body>

</html>