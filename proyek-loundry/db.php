<?php
$host = "localhost";
$user = "root";
$pass = ""; // Jika ada password MySQL, isi di sini
$dbname = "regist_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
