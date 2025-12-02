<?php
$host = 'mysql8'; // Nama service di docker-compose
$user = 'user';
$pass = 'password';
$db   = 'cpftunand';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal: ". $conn->connect_error);
}
echo "<h1>Server Modern (PHP ". phpversion(). ")</h1>";
echo "Koneksi ke MySQL 8.0 Berhasil!";


