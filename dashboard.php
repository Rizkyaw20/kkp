<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'backend/config.php';

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT nik, bagian, nama, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($nik, $bagian, $nama, $role);
$stmt->fetch();
$stmt->close();

echo "Welcome, " . $nama . "!<br>";
echo "NIK: " . $nik . "<br>";
echo "Bagian: " . $bagian . "<br>";
echo "Role: " . $role . "<br>";
?>

<a href="logout.php">Logout</a>