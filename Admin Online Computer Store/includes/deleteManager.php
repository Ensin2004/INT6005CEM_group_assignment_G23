<?php
require_once "dbh.inc.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM admins WHERE id = '$id'");
    echo "<script>alert('Manager deleted successfully!'); window.location.href='../managers.php';</script>";
}
?>
