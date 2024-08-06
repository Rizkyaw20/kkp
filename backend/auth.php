<?php
session_start();

function checkRole($roles = []) {
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], $roles)) {
        $fullUrl = "http://" . $_SERVER['HTTP_HOST'] . "/kkp/login.php";
        header("Location: $fullUrl");
        exit();
    }
}
?>