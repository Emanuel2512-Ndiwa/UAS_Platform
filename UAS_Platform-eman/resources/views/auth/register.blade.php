<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - WashWuzz Laundry</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    @include('components.navbar')

    <section class="register-section">
        <div class="container">
            <div class="register-container">
                <div class="register-header">
                    <h1>Daftar</h1>
                    <p>Bergabung dengan WashWuzz untuk pengalaman laundry lebih baik</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="list-style: none; padding-left: 0;">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="registerForm" method="POST" action="{{ route('register.process') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstname">Nama Depan</label>
                            <input type="text" id="firstname" name="firstname" placeholder="Masukkan nama depan"
                                required value="{{ old('firstname') }}" />
                            @error('firstname')
                                <small class="error">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="lastname">Nama Belakang</label>
                            <input type="text" id="lastname" name="lastname" placeholder="Masukkan nama belakang"
                                required value="{{ old('lastname') }}" />
                            @error('lastname')
                                <small class="error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required
                            value="{{ old('email') }}" />
                        @error('email')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Nomor Telepon</label>
                        <input type="tel" id="phone_number" name="phone_number" placeholder="Masukkan nomor telepon"
                            required value="{{ old('phone_number') }}" />
                        @error('phone_number')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" placeholder="Masukkan alamat lengkap Anda" rows="3"
                            required>{{ old('address') }}</textarea>
                        @error('address')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                                required />
                            @error('password')
                                <small class="error">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Masukkan ulang kata sandi" required />
                        </div>
                    </div>

                    <div class="role-selection">
                        <label for="role">Daftar Sebagai</label>
                        <select name="role" id="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="pelanggan">Pelanggan</option>
                            <option value="karyawan">Karyawan</option>
                            <option value="kurir">Kurir</option>
                        </select>
                    </div>

                    </select>

                    <div class="form-checkbox">
                        <input type="checkbox" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required />
                        <label for="terms">Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a
                                href="#">Kebijakan Privasi</a></label>
                        @error('terms')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="register-button">Buat Akun</button>
                </form>

                <div class="login-link">
                    <p>Sudah memiliki akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
                </div>
            </div>
        </div>
    </section>

    @include('components.footer')
</body>

</html>