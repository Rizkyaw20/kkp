<?php

// Function to check if user is logged in and has a specific role
function is_logged_in() {
    return isset($_SESSION['role']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_general_user() {
    return isset($_SESSION['role']) && $_SESSION['role'] !== 'admin';
}
?>

<div class="nav">
        <a href="../index.php">Home</a>

        <?php if (is_admin()) : ?>
            <a href="../admin/index.php">Dashboard Admin</a>
        <?php endif; ?>

        <?php if (is_general_user()) : ?>
            
        <?php endif; ?>

        <?php if (is_logged_in()) : ?>
            <a href="../logout.php">Logout</a>
        <?php endif; ?>
</div>