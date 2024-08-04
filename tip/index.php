<!DOCTYPE html>
<html>
<head>
    <title>Manajemen TIP</title>
</head>
<body>
    <h1>Manajemen TIP</h1>
    <a href="create_tip.php">Create New Tip</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Bagian</th>
            <th>Line</th>
            <th>Jumlah Tip</th>
            <th>Grup</th>
            <th>Waktu Pembaruan</th>
            <th>Tanggal</th>
            <th>Actions</th>
        </tr>
        <?php
        require '../backend/functions.php';
        $tips = read_tips();
        if ($tips->num_rows > 0) {
            while($row = $tips->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["bagian_id"]."</td>";
                echo "<td>".$row["line_id"]."</td>";
                echo "<td>".$row["jumlah_tip_id"]."</td>";
                echo "<td>".$row["grup_id"]."</td>";
                echo "<td>".$row["waktu_pembaruan"]."</td>";
                echo "<td>".$row["tanggal"]."</td>";
                echo "<td>
                    <a href='update_tip.php?id=".$row["id"]."'>Update</a> | 
                    <a href='delete_tip.php?id=".$row["id"]."'>Delete</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No data available</td></tr>";
        }
        ?>
    </table>
</body>
</html>
