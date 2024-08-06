<?php
require_once 'config.php';

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
            $_SESSION['user_role'] = $role;
            return true;
        } else {
            return "Invalid password.";
        }
    } else {
        return "No user found with that username.";
    }

    $stmt->close();
}

// read user
function read_users($sort_order){
    global $conn;

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    return $result;
}

// Create tip
function create_tip($bagian_id, $line_id, $jumlah_tip_id, $grup_id, $tipe, $waktu_pembaruan = null, $waktu_pengembalian = null, $tanggal) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO manajemen_tip (bagian_id, line_id, jumlah_tip_id, grup_id, tipe, waktu_pembaruan, waktu_pengembalian, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiissss", $bagian_id, $line_id, $jumlah_tip_id, $grup_id, $tipe, $waktu_pembaruan, $waktu_pengembalian, $tanggal);

    if ($stmt->execute()) {
        return "Tip created successfully.";
    } else {
        return "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Read tips
function read_tips($tipe = null, $sort_order = 'ASC', $filter_date = null) {
    global $conn;

    $valid_types = ["pembaruan", "pengembalian"];
    if ($tipe !== null && !in_array($tipe, $valid_types)) {
        return false;
    }

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
            Bagian b ON mt.bagian_id = b.id
        LEFT JOIN 
            Line l ON mt.line_id = l.id
        LEFT JOIN 
            jumlah_tip j ON mt.jumlah_tip_id = j.id
        LEFT JOIN 
            Grup g ON mt.grup_id = g.id
    ";

    $params = [];
    $whereClause = [];

    if ($filter_date) {
        $whereClause[] = "mt.tanggal = ?";
        $params[] = $filter_date;
    }

    if ($tipe) {
        $whereClause[] = "mt.tipe = ?";
        $params[] = $tipe;
    }

    if (!empty($whereClause)) {
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }

    $sql .= " ORDER BY mt.tanggal $sort_order";

    // Prepare and execute statement
    if ($stmt = $conn->prepare($sql)) {
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // 's' for string type
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    } else {
        // Log error in a real application
        error_log("Error preparing statement: " . $conn->error);
        return false;
    }
}

// Update tip
function update_tip($id, $bagian_id, $line_id, $jumlah_tip_id, $grup_id, $tipe, $waktu_pembaruan = null, $waktu_pengembalian = null, $tanggal) {
    global $conn;

    $stmt = $conn->prepare("UPDATE manajemen_tip SET bagian_id = ?, line_id = ?, jumlah_tip_id = ?, grup_id = ?, tipe = ?, waktu_pembaruan = ?, waktu_pengembalian = ?, tanggal = ? WHERE id = ?");
    $stmt->bind_param("iiiissssi", $bagian_id, $line_id, $jumlah_tip_id, $grup_id, $tipe, $waktu_pembaruan, $waktu_pengembalian, $tanggal, $id);

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
