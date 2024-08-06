<?php 
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit;
}


$sort_order = isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'DESC' : 'ASC';
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : null;

$tipe_tip = isset($_GET['tipe']) ? $_GET['tipe'] : "";

if(!isset($_GET['tipe']) || $_GET['tipe'] != "pembaharuan" && $_GET['tipe'] != "pengembalian")
{
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Halaman TIP</title>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this tip?")) {
                window.location.href = 'delete_tip.php?id=' + id + '&tipe=<?php echo $tipe_tip; ?>';
            }
        }
    </script>
</head>
<body>
    <?php 
    include('../backend/nav.php');
    ?>
    <h1>TIP <?php echo $tipe_tip; ?> <?php if(isset($_GET['filter_date'])) : ?> pada <u><?php echo $_GET['filter_date']; ?></u> <?php endif; ?> </h1>
    <a href="create_tip.php?tipe=<?php echo $tipe_tip; ?>">+ Buat Tip</a>

    <form method="get">
        <input type="hidden" name="tipe" value="<?php echo $tipe_tip ?>">

        cari berdasarkan tanggal : 
        <input type="date" name="filter_date" value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>" id="">

        <button name="cari_by_tgl" type="submit">Cari</button>
    </form>

    
    <?php if(isset($_GET['filter_date'])) : ?>
        <a href="index.php?tipe=<?php echo $tipe_tip; ?>">Tampilkan semua</a>
    <?php endif; ?>

    <?php if(!isset($_GET['filter_date'])) : ?>
    <!-- Sorting Form -->
    <form method="get" action="index.php">
        <input type="hidden" name="tipe" value="<?php echo $tipe_tip; ?>">
        <label for="sort">Urutkan:</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="asc" <?php echo $sort_order == 'ASC' ? 'selected' : ''; ?>>Terlama ke Terbaru</option>
            <option value="desc" <?php echo $sort_order == 'DESC' ? 'selected' : ''; ?>>Terbaru ke Terlama</option>
        </select>
    </form>
    
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Bagian</th>
            <th>Line</th>
            <th>Jumlah Tip</th>
            <th>Grup</th>
            <th><?php if($tipe_tip == "pembaharuan") : ?>Waktu Pembaruan<?php else : ?>Waktu Pengembalian<?php endif; ?></th>
            <th>Tanggal</th>
            <?php 
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'):
            ?>
            <th>Actions</th>
            <?php 
            endif;
            ?>
        </tr>
        <?php
        require '../backend/functions.php';

        if($tipe_tip == "pembaharuan"){
            $tips = read_tips("pembaruan", $sort_order, $filter_date);
        }else{
            $tips = read_tips("pengembalian", $sort_order, $filter_date);
        }
        $no = 1;
        if ($tips->num_rows > 0) {
            while($row = $tips->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$no++."</td>";
                echo "<td>".$row["bagian_name"]."</td>";
                echo "<td>".$row["line_name"]."</td>";
                echo "<td>".$row["jumlah_tip_name"]."</td>";
                echo "<td>".$row["grup_name"]."</td>";
                if($row['tipe'] == "pembaruan")
                    echo "<td>".$row["waktu_pembaruan"]."</td>";
                else
                    echo "<td>".$row["waktu_pengembalian"]."</td>'
                    ";
                echo "<td>".$row["tanggal"]."</td>";
                // Check if the user is an admin
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                echo "<td>";
                    echo "<a href='update_tip.php?id=".$row["id"]."&tipe=".$tipe_tip."'>Ubah</a> | ";
                    echo '<button onclick="confirmDelete(' . $row["id"] . ')">Hapus</button>';
                echo "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
</body>
</html>
