<?php
session_start();
require_once "dbh.inc.php";
require_once "audit.php";

// capture actor BEFORE destroying session
$actor_id = $_SESSION['ID'] ?? null;
$actor_role = $_SESSION['role'] ?? null;

// Log logout event
audit_log($conn, $actor_id, $actor_role, 'logout', 'admins', $actor_id, 'Admin logged out');

// Now clear the session
session_unset();
session_destroy();

// Start fresh session id (optional)
session_start();
session_regenerate_id(true);

if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    echo "<script> alert('Session expired. Please log in again.'); window.location.href='../index.php'; </script>";
} else {
    echo "<script> alert('Log out successfully'); window.location.href='../index.php'; </script>";
}
