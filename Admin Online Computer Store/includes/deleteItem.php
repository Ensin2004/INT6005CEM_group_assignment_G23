<?php
session_start();
require_once "dbh.inc.php";
require_once "audit.php";

$itemID = (int)($_GET["item"] ?? 0);
$before = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, item_name, item_status FROM items WHERE id = {$itemID}"));

$ok = mysqli_query($conn, "UPDATE items SET item_status = 'Deleted' WHERE id = {$itemID}");
$after = $before ?: []; $after['item_status'] = 'Deleted';

audit_log(
    $conn,
    $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
    'item_soft_delete','items',$itemID,
    $ok ? "Soft-deleted item #{$itemID}" : "Soft delete failed for item #{$itemID}",
    $before, $after, $ok ? 'success' : 'failure'
);

echo "<script>alert('Item " . ($ok ? "deleted successfully" : "deleted unsuccessful") . "'); window.location.href='../store.php';</script>";
