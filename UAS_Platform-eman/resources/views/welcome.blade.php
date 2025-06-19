@include('components.navbar')

<h1 class="text-center">Selamat Datang di LaundryApp</h1>
<p class="text-center">Silakan login atau register untuk melanjutkan.</p>

<div class="text-center mt-4">
    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
    <a href="{{ route('register') }}" class="btn btn-outline-secondary">Register</a>
</div>

@include('components.footer')
