<?php include('../backend/config.php'); ?>
<?php include('header.php'); ?>

<div class="container">
    <a href="../logout.php">logout</a>
    <h2>Menu</h2>
    <a href="../index.php" class="btn btn-primary">Home</a>
    <a href="manage-pegawai.php" class="btn btn-primary">Manajemen Pegawai</a>
    <a href="../tip/index.php?tipe=pembaharuan" class="btn btn-primary">Manajemen TIP Pembaharuan</a>
    <a href="../tip/index.php?tipe=pengembalian" class="btn btn-primary">Manajemen TIP pengembalian</a>
</div>

<?php include('footer.php'); ?>
