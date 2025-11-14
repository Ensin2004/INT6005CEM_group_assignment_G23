<?php
session_start();
require_once "dbh.inc.php";
require_once "audit.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // BEFORE snapshot (for audit)
    $before = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT about_us_id, about_us_image, about_us_description FROM aboutus WHERE about_us_id = {$id}"));

    if (!$before) {
        echo "<script>alert('Record not found'); window.location.href='../homeEdit.php';</script>";
        exit;
    }

    $ok = mysqli_query($conn, "DELETE FROM aboutus WHERE about_us_id = {$id}");
    if ($ok) {
        audit_log(
            $conn,
            $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
            'content_delete', 'aboutus', $id,
            "Deleted About Us entry #{$id}",
            $before, null
        );
        echo "<script>alert('Delete Successfully'); window.location.href='../homeEdit.php';</script>";
    } else {
        echo "<script>alert('Delete failed'); window.location.href='../homeEdit.php';</script>";
    }
}
