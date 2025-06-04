<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex justify-content-center align-items-center" style="height: 100vh;">
<div class="bg-secondary p-4 rounded" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-3">Login</h3>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if($errors->has('error'))
        <div class="alert alert-danger text-center">{{ $errors->first('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <input type="text" name="username" class="form-control mb-3" placeholder="Username" value="{{ old('username') }}" required autofocus>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

        <button type="submit" class="btn btn-success w-100">Login</button>

        <p class="mt-3 text-center">
            Belum punya akun? <a href="{{ route('regist') }}" class="text-light">Daftar</a>
        </p>
    </form>
</div>
</body>
</html>
