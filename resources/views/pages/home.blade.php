<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home - WashWuzz</title>
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
</head>
<body>
    @include('components.navbar')

    <section class="hero">
        <div class="container">
            <h1>Selamat Datang di WashWuzz!</h1>
            <p>Layanan laundry cepat, terjangkau, dan terpercaya.</p>
        </div>
    </section>

    <section class="services">
        <div class="container">
            <h2>Layanan Kami</h2>
            <div class="services-grid">
                @foreach ($services as $service)
                    <div class="service-card">
                        <img src="{{ $service->image }}" alt="{{ $service->name }}" style="width:100px">
                        <h3>{{ $service->name }}</h3>
                        <p>Harga: Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                        <a href="{{ route('services.show', $service->id) }}">Lihat Detail</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
