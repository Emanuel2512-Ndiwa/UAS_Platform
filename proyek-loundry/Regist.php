<?php
session_start();
include "db.php";

$error = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $pass = md5($_POST['pass']);
    $no_telepon = $_POST['no_telepon'];
    $email = $_POST['email'];

    if ($role == 'admin') {
        $error = "Role admin tidak bisa dipilih!";
    } else {
        $cek = $conn->query("SELECT * FROM users WHERE username = '$username'");
        if ($cek->num_rows > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, role, pass, no_telepon, email) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $role, $pass, $no_telepon, $email);
            if ($stmt->execute()) {
                echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
            } else {
                $error = "Gagal mendaftar!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="bg-secondary p-4 rounded" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-3">Registrasi Pengguna</h3>
        <?php if ($error) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
            <select name="role" class="form-control mb-2" required>
                <option value="">Pilih Role</option>
                <option value="karyawan">Karyawan</option>
                <option value="kurir">Kurir</option>
                <option value="pelanggan">Pelanggan</option>
            </select>
            <input type="password" name="pass" class="form-control mb-2" placeholder="Password" required>
            <input type="text" name="no_telepon" class="form-control mb-2" placeholder="No Telepon" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
            <p class="mt-3 text-center">Sudah punya akun? <a href="login.php" class="text-light">Login</a></p>
        </form>
    </div>
</body>
</html>
