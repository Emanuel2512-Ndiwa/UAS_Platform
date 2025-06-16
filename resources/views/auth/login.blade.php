<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - WashWuzz Laundry</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <!-- Header -->
    @include('components.navbar')

    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <h1>Masuk</h1>
                    <p>Selamat datang kembali di WashWuzz</p>
                </div>

                @if ($errors->any())
                    <div class="error mb-4" style="color: #f44336;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required
                            value="{{ old('email') }}" autofocus />
                        @error('email')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi Anda"
                            required />
                        @error('password')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="role-selection">
                        <label for="role">Sesuaikan Status Anda</label>
                        <select name="role" id="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="pelanggan">Pelanggan</option>
                            <option value="karyawan">Karyawan</option>
                            <option value="kurir">Kurir</option>
                        </select>
                    </div>
                    </select>
                    <div class="form-footer">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" />
                            <label for="remember">Ingat saya</label>
                        </div>
                        <a href="#" class="forgot-password">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="login-button">Masuk</button>
                </form>

                <div class="register-link">
                    <p>Belum memiliki akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
                </div>

                <div class="social-login">
                    <div class="social-login-title">
                        <span>Atau masuk dengan</span>
                    </div>
                    <div class="social-buttons">
                        <button class="social-button"><i class="fab fa-google"></i></button>
                        <button class="social-button"><i class="fab fa-facebook"></i></button>
                        <button class="social-button"><i class="fab fa-twitter"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>