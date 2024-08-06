<?php
require '../backend/config.php';
include '../backend/auth.php';
include '../backend/functions.php';

// Check if user is logged in and has the correct role
checkRole(['admin']);

// Get the ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID is required");
}

$id = intval($_GET['id']);

$tipe_tip = isset($_GET['tipe']) ? $_GET['tipe'] : "";

if(!isset($_GET['tipe']) || $_GET['tipe'] != "pembaharuan" && $_GET['tipe'] != "pengembalian")
{
    header("Location: ../index.php");
    exit;
}

// Fetch existing data
$sql = "
    SELECT 
        mt.*, 
        b.name AS bagian_name, 
        l.name AS line_name, 
        j.name AS jumlah_tip_name, 
        g.name AS grup_name
    FROM 
        manajemen_tip mt
    LEFT JOIN 
        bagian b ON mt.bagian_id = b.id
    LEFT JOIN 
        line l ON mt.line_id = l.id
    LEFT JOIN 
        jumlah_tip j ON mt.jumlah_tip_id = j.id
    LEFT JOIN 
        grup g ON mt.grup_id = g.id
    WHERE 
        mt.id = ?
";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        die("No data found");
    }
    
    $stmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch options for selects
function fetchOptions($table) {
    global $conn;
    $options = [];
    $sql = "SELECT id, name FROM $table";
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $options[$row['id']] = $row['name'];
        }
    }
    return $options;
}

$bagian_options = fetchOptions('Bagian');
$line_options = fetchOptions('Line');
$jumlah_tip_options = fetchOptions('Jumlah_Tip');
$grup_options = fetchOptions('Grup');

if(isset($_POST['submit'])) {
    $bagian_id = $_POST['bagian_id'];
    $line_id = $_POST['line_id'];
    $jumlah_tip_id = $_POST['jumlah_tip_id'];
    $grup_id = $_POST['grup_id'];
    $tipe = ($tipe_tip == "pembaharuan") ? "pembaruan" : "pengembalian"; 
    $waktu_pembaruan = isset($_POST['waktu_pembaruan']) ? $_POST['waktu_pembaruan'] : null;
    $waktu_pengembalian = isset($_POST['waktu_pengembalian']) ? $_POST['waktu_pengembalian'] : null;
    $tanggal = $_POST['tanggal'];

    $result = update_tip($id, $bagian_id, $line_id, $jumlah_tip_id, $grup_id, $tipe, $waktu_pembaruan, $waktu_pengembalian, $tanggal);
    echo $result;

    // redirect with delay
    header("Refresh: 2; url=index.php?tipe=$tipe_tip");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Tip</title>
</head>
<body>
<h1>Update Tip</h1>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">

        Bagian ID <span class="dot-red">*</span>: 
        <select name="bagian_id" required>
            <option value="">Select Bagian</option>
            <?php foreach ($bagian_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>" <?php echo $data['bagian_id'] == $id ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($name); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        Line ID <span class="dot-red">*</span>: 
        <select name="line_id" required>
            <option value="">Select Line</option>
            <?php foreach ($line_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>" <?php echo $data['line_id'] == $id ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($name); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        Jumlah Tip ID <span class="dot-red">*</span>: 
        <select name="jumlah_tip_id" required>
            <option value="">Select Jumlah Tip</option>
            <?php foreach ($jumlah_tip_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>" <?php echo $data['jumlah_tip_id'] == $id ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($name); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        Grup ID <span class="dot-red">*</span>: 
        <select name="grup_id" required>
            <option value="">Select Grup</option>
            <?php foreach ($grup_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>" <?php echo $data['grup_id'] == $id ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($name); ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        
        Waktu Pembaruan <span class="dot-red">*</span>: 
        <input type="time" name="waktu_pembaruan" value="<?php echo htmlspecialchars($data['waktu_pembaruan']); ?>" required><br>
        
        Tanggal <span class="dot-red">*</span>: 
        <input type="date" name="tanggal" value="<?php echo htmlspecialchars($data['tanggal']); ?>" required><br>
        
        <input type="submit" name="submit" value="Update Tip">
    </form>
</body>
</html>