<?php
session_start();
require_once "dbh.inc.php";
require_once "audit.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // BEFORE (for context)
    $before = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, admin_name, admin_email, role FROM admins WHERE id = {$id}"));

    if (mysqli_query($conn, "DELETE FROM admins WHERE id = {$id}")) {
        audit_log(
            $conn,
            $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
            'admin_delete', 'admins', $id,
            "Deleted admin #{$id}",
            $before, null
        );
        echo "<script>alert('Manager deleted successfully!'); window.location.href='../managers.php';</script>";
    } else {
        echo "<script>alert('Delete failed'); window.location.href='../managers.php';</script>";
    }
}
