<?php
require '../backend/config.php';
include '../backend/auth.php';
include '../backend/functions.php';

 // Function to fetch options from a table
function fetch_options($table) {
    global $conn;
    $options = [];
    $sql = "SELECT id, name FROM $table";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $options[$row['id']] = $row['name'];
        }
    }
    return $options;
}

// Fetch options
$bagian_options = fetch_options('bagian');
$line_options = fetch_options('line');
$jumlah_tip_options = fetch_options('jumlah_tip');
$grup_options = fetch_options('grup');

$tipe_tip = isset($_GET['tipe']) ? $_GET['tipe'] : "";

if(!isset($_GET['tipe']) || $_GET['tipe'] != "pembaharuan" && $_GET['tipe'] != "pengembalian")
{
    header("Location: ../index.php");
    exit;
}

if(isset($_POST['submit'])) {
    $bagian_id = $_POST['bagian_id'];
    $line_id = $_POST['line_id'];
    $jumlah_tip_id = $_POST['jumlah_tip_id'];
    $grup_id = $_POST['grup_id'];
    $tipe = ($tipe_tip == "pembaharuan") ? "pembaruan" : "pengembalian"; 
    $waktu_pembaruan = isset($_POST['waktu_pembaruan']) ? $_POST['waktu_pembaruan'] : null;
    $waktu_pengembalian = isset($_POST['waktu_pengembalian']) ? $_POST['waktu_pengembalian'] : null;
    $tanggal = $_POST['tanggal'];

    $result = create_tip($bagian_id, $line_id, $jumlah_tip_id, $grup_id, $tipe, $waktu_pembaruan,  $waktu_pengembalian, $tanggal);
    echo $result;

    // redirect with delay
    header("Refresh: 2; url=index.php?tipe=$tipe_tip");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create TIP</title>
    <style>
        /* CSS untuk menambahkan indikator titik merah pada input yang wajib diisi */
    .dot-red {
        position: relative;
        color: red;
    } 

    </style>
</head>
<body>

    <a href="index.php?tipe=<?php echo $tipe_tip; ?>">Kembali</a>
    <h1>Create TIP</h1>
    <form method="post">
        Bagian ID <span class="dot-red">*</span>: 
        <select name="bagian_id" required>
            <option value="">Select Bagian</option>
            <?php foreach ($bagian_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
        </select><br>

        Line ID <span class="dot-red">*</span>: 
        <select name="line_id" required>
            <option value="">Select Line</option>
            <?php foreach ($line_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
        </select><br>

        Jumlah Tip ID <span class="dot-red">*</span>: 
        <select name="jumlah_tip_id" required>
            <option value="">Select Jumlah Tip</option>
            <?php foreach ($jumlah_tip_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
        </select><br>

        Grup ID <span class="dot-red">*</span>: 
        <select name="grup_id" required>
            <option value="">Select Grup</option>
            <?php foreach ($grup_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
        </select><br>
        
        <?php 
        if($tipe_tip == "pembaharuan"):
        ?>    
        Waktu Pembaruan <span class="dot-red">*</span>: <input type="time" name="waktu_pembaruan" required><br>
        <?php 
        endif;
        ?>

        <?php 
        if($tipe_tip == "pengembalian"):
        ?>    
        Waktu Pengembalian <span class="dot-red">*</span>: <input type="time" name="waktu_pengembalian" required><br>
        <?php 
        endif;
        ?>
        
        Tanggal <span class="dot-red">*</span>: <input type="date" name="tanggal" required><br>
        <input type="submit" name="submit" value="submit">
    </form>

    <p>Note: <span class="dot-red">*</span> wajib diisi</p>
</body>
</html>
