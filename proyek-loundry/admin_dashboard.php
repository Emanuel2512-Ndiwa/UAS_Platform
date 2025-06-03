<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light text-center d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div>
        <h2>Selamat Datang Admin, <?= $_SESSION['username']; ?> 👑</h2>
        <p>Anda masuk sebagai <strong>Admin</strong> - Manajemen sistem laundry.</p>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>
</body>
</html>
