<?php
require 'backend/config.php';

// Function to insert data into a table
function migrate_data($table, $values) {
    global $conn;

    $placeholders = implode(',', array_fill(0, count($values), '(?)'));
    $stmt = $conn->prepare("INSERT INTO $table (name) VALUES $placeholders");

    if ($stmt) {
        $types = str_repeat('s', count($values)); // 's' for string type
        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) {
            echo "Data inserted into $table successfully.<br>";
        } else {
            echo "Error inserting data into $table: " . $stmt->error . "<br>";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error . "<br>";
    }
}

// Data to be inserted
$bagian_values = ['D55L', 'D26A', 'D74A'];
$line_values = ['6-5-1', '6-5-2', '6-5-11', '6-5-12'];
$jumlah_tip_values = ['12', '24', '20'];

// Migrate data
migrate_data('bagian', $bagian_values);
migrate_data('line', $line_values);
migrate_data('jumlah_tip', $jumlah_tip_values);

// Close connection
$conn->close();