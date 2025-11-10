<?php
require_once "dbh.inc.php";

if (isset($_GET['id']) && isset($_GET['action'])) {
    $adminId = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'ban') {
        $sql = "UPDATE admins SET account_status = 'banned' WHERE id = ?";
    } elseif ($action === 'unban') {
        $sql = "UPDATE admins SET account_status = 'active' WHERE id = ?";
    } else {
        die("Invalid action");
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);
    
    if ($stmt->execute()) {
        header("Location: ../managers.php?success=" . $action);
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Invalid request";
}
?>
