<header>
    <div class="container header-content">
        <div class="logo">
            <span class="logo-icon">
                <img src="{{ asset('assets/logo/logo.png') }}" alt="">
            </span>
            <span>WashWuzz</span>
        </div>
        <button class="mobile-menu-btn" id="menuToggle">☰</button>
        <nav id="mainNav">
            <ul>
                <li><a href="/">Beranda</a></li>
                <li><a href="/service">Layanan</a></li>
                @auth
                    <li><a href="/order">Pesanan</a></li>
                @endauth
                <li><a href="/about">Tentang Kami</a></li>
                <li><a href="/contact">Kontak</a></li>
                <li>
                    @auth
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="logout-btn">
                            Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}">Masuk</a>
                    @endauth
                </li>
            </ul>
        </nav>
    </div>
</header>