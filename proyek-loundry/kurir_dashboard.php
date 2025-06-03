<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'kurir') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kurir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-primary text-white text-center d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div>
        <h2>Selamat Datang Kurir, <?= $_SESSION['username']; ?> 🚚</h2>
        <p>Anda bertugas menjemput dan mengantar pakaian pelanggan.</p>
        <a href="logout.php" class="btn btn-light mt-3">Logout</a>
    </div>
</body>
</html>
