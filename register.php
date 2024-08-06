<?php
session_start();
include('backend/auth.php');
checkRole(['admin']);

require 'backend/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $nik = $_POST['nik'];
    $bagian = $_POST['bagian'];
    $nama = $_POST['nama'];

    $result = register($username, $password, $role, $nik, $bagian, $nama);
    echo $result;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css" media="screen" title="no title">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <title>Register Page</title>
</head>

<body>
    <div class="input">
        <h1>REGISTER</h1>
        <form action="register.php" method="POST">
            <div class="box-input">
                <i class="fas fa-user"></i>
                <input type="text" name="nama" placeholder="Full Name">
            </div>
            <div class="box-input">
                <i class="fas fa-address-book"></i>
                <input type="text" name="username" placeholder="Username">
            </div>
            <div class="box-input">
                <i class="fas fa-graduation-cap"></i>
                <input type="text" name="institution" placeholder="Institution">
            </div>
            <div class="box-input">
                <i class="fas fa-envelope-open-text"></i>
                <input type="text" name="bagian" placeholder="bagian">
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password">
            </div>
            <a href="index.html">
                <button type="submit" name="register" class="btn-input">Register</button>
            </a>
            <!-- <div class="bottom">
                <p>Sudah punya akun?
                    <a href="index.html">Login disini</a>
                </p>
            </div> -->
        </form>
    </div>
</body>

</html>