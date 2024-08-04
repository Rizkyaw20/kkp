<?php
require '../backend/config.php';
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

?>
<!DOCTYPE html>
<html>
<head>
    <title>Create TIP</title>
</head>
<body>
    <h1>Create TIP</h1>
    <form method="post" action="create_tip_process.php">
        Bagian ID: 
        <select name="bagian_id" required>
            <option value="">Select Bagian</option>
            <?php foreach ($bagian_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
        </select><br>

        Line ID: 
        <select name="line_id" required>
            <option value="">Select Line</option>
            <?php foreach ($line_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
        </select><br>

        Jumlah Tip ID: 
        <select name="jumlah_tip_id" required>
            <option value="">Select Jumlah Tip</option>
            <?php foreach ($jumlah_tip_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
        </select><br>

        Grup ID: 
        <select name="grup_id" required>
            <option value="">Select Grup</option>
            <?php foreach ($grup_options as $id => $name) : ?>
                <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
            <?php endforeach; ?>
        </select><br>
        
        Waktu Pembaruan: <input type="time" name="waktu_pembaruan" required><br>
        Tanggal: <input type="date" name="tanggal" required><br>
        <input type="submit" value="Create Tip">
    </form>
</body>
</html>
