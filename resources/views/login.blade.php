<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .form-control {
            padding-left: 2.5rem;
        }

        .form-group {
            position: relative;
        }

        a.text-light:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-dark text-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="bg-secondary p-4 rounded shadow w-100" style="max-width: 400px;">
        <h3 class="text-center mb-4">Login</h3>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        @if($errors->has('error'))
            <div class="alert alert-danger text-center">{{ $errors->first('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group mb-3">
                <i class="form-icon bi bi-person-fill"></i>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                       placeholder="Username" value="{{ old('username') }}" required autofocus aria-label="Username">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <i class="form-icon bi bi-lock-fill"></i>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password" required aria-label="Password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success w-100">Login</button>

            <p class="mt-3 text-center">
                Belum punya akun? <a href="{{ route('regist') }}" class="text-light">Daftar</a>
            </p>
        </form>
    </div>

    <!-- Bootstrap icons CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
</body>
</html>
