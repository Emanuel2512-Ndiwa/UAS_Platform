<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex justify-content-center align-items-center" style="height: 100vh;">
<div class="bg-secondary p-4 rounded" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-3">Registrasi Pengguna</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('regist.post') }}">
        @csrf
        <input type="text" name="username" class="form-control mb-2" placeholder="Username" value="{{ old('username') }}" required>
        <select name="role" class="form-control mb-2" required>
            <option value="">Pilih Role</option>
            <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
            <option value="kurir" {{ old('role') == 'kurir' ? 'selected' : '' }}>Kurir</option>
            <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
        </select>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <input type="password" name="password_confirmation" class="form-control mb-2" placeholder="Konfirmasi Password" required>
        <input type="text" name="no_telepon" class="form-control mb-2" placeholder="No Telepon" value="{{ old('no_telepon') }}" required>
        <input type="email" name="email" class="form-control mb-3" placeholder="Email" value="{{ old('email') }}" required>

        <button type="submit" class="btn btn-primary w-100">Daftar</button>

        <p class="mt-3 text-center">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-light">Login</a>
        </p>
    </form>
</div>
</body>
</html>
