<?php 
session_start();
include('../backend/config.php');
include('../backend/functions.php');


if (!isset($_SESSION['username']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $nik = $_POST['nik'];
    $bagian = $_POST['bagian'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $result = register($username, $password, $role, $nik, $bagian, $nama);
    echo $result;
}
?>

<?php include('header.php'); ?>

<div class="container">
    <div class="input">
        <a href="index.php" class="btn-input">kembali</a>
        <br><br><br>
        <h1>Buat User Baru</h1>
        <form method="POST">
            <div class="box-input">
                <i class="fas fa-user"></i>
                <input type="text" name="nik" placeholder="nik" required>
            </div>
            <div class="box-input">
                <i class="fas fa-user"></i>
                <input type="text" name="nama" placeholder="Full Name" required>
            </div>
            <div class="box-input">
                <i class="fas fa-address-book"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <i class="fas fa-graduation-cap"></i>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="leader">Leader</option>
                    <option value="foreman">Foreman</option>
                    <option value="sgl_d55l">SGL D55L</option>
                    <option value="sgl_d74a">SGL D74A</option>
                    <option value="sgl_d26a">SGL D26A</option>
                    <option value="ed_coating">ED Coating</option>
                </select>
            </div>
            <div class="box-input">
                <i class="fas fa-envelope-open-text"></i>
                <input type="text" name="bagian" placeholder="bagian" required>
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <a href="index.html">
                <button type="submit" name="register" class="btn-input">Register</button>
            </a>
        </form>
    </div>
</div>

<?php include('footer.php'); ?>