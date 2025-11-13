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

if (!$conn) {
    die("Database connection failed");
} else {
    $orderID = $_GET["orderID"];
    $action = $_GET["action"];

    switch ($action) {
         case "rejectOrder":
            //get remarks from the form
            $remarks = $_GET["remarks"];

            //change status to rejected and save remarks
            $change_status_query = "UPDATE orders SET status_id = 6, remarks = '$remarks' WHERE id = $orderID;";
            if (!mysqli_query($conn, $change_status_query)) {
                echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>";
                exit();
            }

            //add stock_qty back
            $order_items = mysqli_query($conn, "SELECT * FROM orderitems INNER JOIN orders ON orderitems.order_id = orders.id WHERE orders.id = $orderID;");
            while ($row = mysqli_fetch_assoc($order_items)) {
                $qty = $row["qty"];
                $itemID = $row["item_id"];
                $reduce_stock_query = "UPDATE items SET stock_qty = stock_qty + $qty WHERE id = $itemID;";
                if (!mysqli_query($conn, $reduce_stock_query)) {
                    echo "<script>alert('Item stock updated unsuccessful'); window.location.href='../order.php';</script>";
                    exit();
                }
            }

            echo "<script>window.history.go(-1);</script>";
            break;

        case "nextProcess":
            //change status to next process
            $change_status_query = "UPDATE orders SET status_id = status_id + 1 WHERE id = $orderID;";
            if (!mysqli_query($conn, $change_status_query)) {
                echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>";
                exit();
            }

            echo "<script>window.history.go(-1);</script>";
            break;

        case "previousProcess":
            //change status to previous process
            $change_status_query = "UPDATE orders SET status_id = status_id - 1 WHERE id = $orderID;";
            if (!mysqli_query($conn, $change_status_query)) {
                echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>";
                exit();
            }

            echo "<script>window.history.go(-1);</script>";
            break;

        default:
            echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>";
            break;
    }
}
