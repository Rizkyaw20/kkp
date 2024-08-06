<?php 
require '../backend/config.php';
include '../backend/auth.php';
include '../backend/functions.php';

checkRole(['admin']);

$tipe_tip = isset($_GET['tipe']) ? $_GET['tipe'] : "";

if(!isset($_GET['tipe']) || $_GET['tipe'] != "pembaharuan" && $_GET['tipe'] != "pengembalian")
{
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and execute delete query
    $sql = "DELETE FROM manajemen_tip WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // Redirect back to the index page
            header("Location: index.php?tipe=$tipe_tip");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid ID";
}
?>