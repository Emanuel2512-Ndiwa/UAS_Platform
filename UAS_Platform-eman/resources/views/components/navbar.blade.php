<header>
    <div class="container header-content d-flex justify-content-between align-items-center">
        <div class="logo d-flex align-items-center">
            <span class="logo-icon">
                <img src="{{ asset('assets/logo/logo.png') }}" alt="" style="height: 30px;">
            </span>
            <span class="ml-2 font-weight-bold">WashWuzz</span>
        </div>

        <button class="mobile-menu-btn" id="menuToggle">☰</button>

        <nav id="mainNav" class="d-none d-lg-block">
            <ul class="d-flex gap-3 align-items-center list-unstyled m-0">
                <li><a href="{{ route('home') }}">Beranda</a></li>

                @auth
                    @if (Auth::user()->role === 'pelanggan')
                        <li><a href="{{ route('transaction.index') }}">Pesanan Saya</a></li>
                    @elseif (Auth::user()->role === 'kurir')
                        <li><a href="{{ route('dashboard.kurir') }}">Pesanan Kurir</a></li>
                    @elseif (Auth::user()->role === 'karyawan')
                        <li><a href="{{ route('dashboard.karyawan') }}">Dashboard Karyawan</a></li>
                    @elseif (Auth::user()->role === 'admin')
                        <li><a href="{{ route('dashboard.admin') }}">Admin</a></li>
                    @endif
                @endauth

                <li><a href="/about">Tentang Kami</a></li>
                <li><a href="/contact">Kontak</a></li>

                @auth
                    <li class="ml-3">Hai, {{ Auth::user()->name }}</li>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="btn btn-sm btn-outline-danger">Keluar</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                @endauth
            </ul>
        </nav>
    </div>
</header>
