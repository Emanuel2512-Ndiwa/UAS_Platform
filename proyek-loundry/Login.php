<?php
session_start();
include "db.php";

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $pass = md5($_POST['pass']);

    $result = $conn->query("SELECT * FROM users WHERE username='$username' AND pass='$pass'");

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect sesuai role
        switch ($user['role']) {
            case 'admin':
                header("Location: admin_dashboard.php");
                break;
            case 'karyawan':
                header("Location: karyawan_dashboard.php");
                break;
            case 'kurir':
                header("Location: kurir_dashboard.php");
                break;
            case 'pelanggan':
                header("Location: pelanggan_dashboard.php");
                break;
            default:
                header("Location: dashboard.php"); // fallback
        }
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="bg-secondary p-4 rounded" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-3">Login</h3>
        <?php if ($error) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="pass" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-success w-100">Login</button>
            <p class="mt-3 text-center">Belum punya akun? <a href="regist.php" class="text-light">Daftar</a></p>
        </form>
    </div>
</body>
</html>
