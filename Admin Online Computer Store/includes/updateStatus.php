<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();
require_once "dbh.inc.php";
require_once "audit.php";

if (!$conn) {
    die("Database connection failed");
} else {
    $orderID = $_GET["orderID"];
    $action = $_GET["action"];

    $before = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, status_id, remarks FROM orders WHERE id = {$orderID}"));
    if (!$before) {
        echo "<script>alert('Order not found'); window.location.href='../order.php';</script>";
        exit;
    }

    switch ($action) {
        case "rejectOrder":
            $remarks = $_GET["remarks"] ?? '';
            $ok1 = mysqli_query($conn, "UPDATE orders SET status_id = 6, remarks = '".mysqli_real_escape_string($conn,$remarks)."' WHERE id = {$orderID};");
            if (!$ok1) {
                audit_log($conn, $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                        'order_status_change','orders',$orderID,
                        'Reject failed', $before, null, 'failure');
                echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>";
                exit();
            }

            // restock
            $ok2 = true;
            $order_items = mysqli_query($conn, "SELECT orderitems.qty, orderitems.item_id FROM orderitems INNER JOIN orders ON orderitems.order_id = orders.id WHERE orders.id = {$orderID};");
            while ($row = mysqli_fetch_assoc($order_items)) {
                $qty = (int)$row["qty"];
                $itemID = (int)$row["item_id"];
                if (!mysqli_query($conn, "UPDATE items SET stock_qty = stock_qty + {$qty} WHERE id = {$itemID};")) {
                    $ok2 = false; break;
                }
            }

            $after = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, status_id, remarks FROM orders WHERE id = {$orderID}"));
            audit_log($conn, $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                    'order_status_change','orders',$orderID,
                    $ok2 ? 'Order rejected (restocked items)' : 'Order rejected (restock failed for some items)',
                    $before, $after, $ok2 ? 'success' : 'partial');

            echo "<script>window.history.go(-1);</script>";
            break;

        case "nextProcess":
            $ok = mysqli_query($conn, "UPDATE orders SET status_id = status_id + 1 WHERE id = {$orderID};");
            $after = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, status_id, remarks FROM orders WHERE id = {$orderID}"));
            audit_log($conn, $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                    'order_status_change','orders',$orderID,
                    $ok ? 'Advanced order process' : 'Advance order process failed',
                    $before, $after, $ok ? 'success' : 'failure');
            if (!$ok) { echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>"; exit(); }
            echo "<script>window.history.go(-1);</script>";
            break;

        case "previousProcess":
            $ok = mysqli_query($conn, "UPDATE orders SET status_id = status_id - 1 WHERE id = {$orderID};");
            $after = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, status_id, remarks FROM orders WHERE id = {$orderID}"));
            audit_log($conn, $_SESSION['ID'] ?? null, $_SESSION['role'] ?? null,
                    'order_status_change','orders',$orderID,
                    $ok ? 'Reverted order process' : 'Revert order process failed',
                    $before, $after, $ok ? 'success' : 'failure');
            if (!$ok) { echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>"; exit(); }
            echo "<script>window.history.go(-1);</script>";
            break;

        default:
            echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>";
            break;
    }

}
