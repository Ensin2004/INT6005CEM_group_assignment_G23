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
         case "notReceived":
            //get remarks from the form
            $remarks = $_GET["remarks"];

            //change status to not received and save remarks
            $change_status_query = "UPDATE orders SET status_id = 7, remarks = '$remarks' WHERE id = $orderID;";
            if (!mysqli_query($conn, $change_status_query)) {
                echo "<script>alert('Status updated unsuccessful'); window.location.href='../order.php';</script>";
                exit();
            }

            echo "<script>window.history.go(-1);</script>";
            break;

        case "received":
            //change status to completed
            $change_status_query = "UPDATE orders SET status_id = 5 WHERE id = $orderID;";
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
