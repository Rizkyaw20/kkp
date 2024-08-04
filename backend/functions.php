<?php
require 'config.php';

function register($username, $password, $role, $nik, $bagian, $nama) {
    global $conn;
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, nik, bagian, nama) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $hashed_password, $role, $nik, $bagian, $nama);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        return "Register successful.";
    } else {
        return "Error: " . $stmt->error;
    }

    $stmt->close();
}

function login($username, $password) {
    global $conn;
    
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT id, password, role, nik, bagian, nama FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role, $nik, $bagian, $nama);
    
    // Check if user exists and password is correct
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Start session and set session variables
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['nik'] = $nik;
            $_SESSION['bagian'] = $bagian;
            $_SESSION['nama'] = $nama;
            return true;
        } else {
            return "Invalid password.";
        }
    } else {
        return "No user found with that username.";
    }

    $stmt->close();
}

// Create tip
function create_tip($bagian_id, $line_id, $jumlah_tip_id, $grup_id, $waktu_pembaruan, $tanggal) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO manajemen_tip (bagian_id, line_id, jumlah_tip_id, grup_id, waktu_pembaruan, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiss", $bagian_id, $line_id, $jumlah_tip_id, $grup_id, $waktu_pembaruan, $tanggal);

    if ($stmt->execute()) {
        return "Tip created successfully.";
    } else {
        return "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Read tips
function read_tips() {
    global $conn;

    $sql = "SELECT * FROM manajemen_tip";
    $result = $conn->query($sql);

    return $result;
}

// Update tip
function update_tip($id, $bagian_id, $line_id, $jumlah_tip_id, $grup_id, $waktu_pembaruan, $tanggal) {
    global $conn;

    $stmt = $conn->prepare("UPDATE manajemen_tip SET bagian_id = ?, line_id = ?, jumlah_tip_id = ?, grup_id = ?, waktu_pembaruan = ?, tanggal = ? WHERE id = ?");
    $stmt->bind_param("iiiissi", $bagian_id, $line_id, $jumlah_tip_id, $grup_id, $waktu_pembaruan, $tanggal, $id);

    if ($stmt->execute()) {
        return "Tip updated successfully.";
    } else {
        return "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Delete tip
function delete_tip($id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM manajemen_tip WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return "Tip deleted successfully.";
    } else {
        return "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
