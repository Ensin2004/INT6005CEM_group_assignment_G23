<?php
function requireRole($role) {
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../index.php");
        exit;
    }
    if ($_SESSION['role'] !== $role) {
        echo "Access denied.";
        exit;
    }
}
?>
