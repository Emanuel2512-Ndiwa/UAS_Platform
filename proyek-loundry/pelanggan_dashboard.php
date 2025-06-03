<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-info text-dark text-center d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div>
        <h2>Halo Pelanggan, <?= $_SESSION['username']; ?> 🧺</h2>
        <p>Selamat datang di layanan laundry kami!</p>
        <a href="logout.php" class="btn btn-dark mt-3">Logout</a>
    </div>
</body>
</html>
