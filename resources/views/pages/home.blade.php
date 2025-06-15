<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WashWuzz | Laundry Platform</title>
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Header -->
    @include('components.navbar')

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Pakaian Bersih. Hidup Bersih.</h1>
                <p>Biarkan kami yang mengurus cucian Anda, agar Anda bisa fokus pada hal yang lebih penting. Layanan
                    laundry profesional dengan penjemputan dan pengantaran gratis ke rumah Anda.</p>
                <a href="/service" class="cta-button">Pesan Sekarang</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Kenapa Memilih Kami</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h3>Pelayanan Cepat</h3>
                    <p>Pakaian bersih kembali dalam 24 jam dengan opsi layanan ekspres kami.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <h3>Harga Terjangkau</h3>
                    <p>Harga bersaing dengan paket langganan untuk lebih hemat dalam penggunaan rutin.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3>Jaminan Kualitas</h3>
                    <p>Jaminan 100% kepuasan atau kami akan mencuci ulang tanpa biaya tambahan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Services Section -->
    <section class="services">
        <div class="container">
            <h2 class="section-title">Layanan Populer</h2>
            <div class="services-grid">
                @foreach ($services as $service)
                    <div class="service-card">
                        <div class="service-image">
                            <img src="{{ $service->image }}" alt="">
                        </div>
                        <div class="service-content">
                            <h3 class="service-title">{{ $service->name }}<span class="popular-tag">Populer</span></h3>
                            <div class="service-price">Mulai dari Rp
                                {{ number_format($service['price'], 0, ',', '.') }}/{{ $service['name'] == 'Cuci Regular' ? 'kg' : 'item' }}</div>
                            <p class="service-description">Layanan standar kami untuk mencuci, mengeringkan, dan melipat
                                pakaian. Cocok untuk kebutuhan harian Anda dengan deterjen dan pewangi premium.</p>
                            <a href="{{ url('/service/detail/' . $service['id']) }}" class="service-button">Pilih</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="/service" class="another-service-btn">Lihat Layanan Lain</a>
        </div>
    </section>

    <!-- Footer -->
    @include('components.footer')

    <script>
        // Toggle menu untuk tampilan mobile
        const menuToggle = document.getElementById('menuToggle');
        const mainNav = document.getElementById('mainNav');

        menuToggle.addEventListener('click', () => {
            mainNav.classList.toggle('active');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                title: 'Login Berhasil',
                icon: 'success',
            });
        </script>
    @endif
</body>

</html>