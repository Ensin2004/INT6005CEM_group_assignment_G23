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

if (isset($_GET['id']) && isset($_GET['data']) && isset($_GET['stock']) && isset($_GET['qty'])) {
    $id = $_GET['id'];
    $action = $_GET['data'];
    $stockQty = $_GET['stock'];
    $itemQty = $_GET['qty'];

    if ($action === 'decrease') {
        if ($itemQty > 1) {
            $query = "UPDATE cart SET qty = qty - 1 WHERE id = $id";
            $result = mysqli_query($conn, $query);
            echo "<script>window.history.go(-1);</script>";
            exit();
        } else {
            echo "<script>window.history.go(-1);</script>";
            exit();
        }
    } elseif ($action === 'increase') {
        $query = "UPDATE cart SET qty = qty + 1 WHERE id = $id";
        $result = mysqli_query($conn, $query);
        echo "<script>window.history.go(-1);</script>";
        exit();
    }
}

if (isset($_POST['id']) && isset($_POST['quantity']) && isset($_POST['stock'])) {
    $id = $_POST['id'];
    $qty = $_POST['quantity'];
    $stockQty = $_POST['stock'];

    if ($qty < 1) {
        $qty = 1;
    }

    $query = "UPDATE cart SET qty = $qty WHERE id = $id";
    $result = mysqli_query($conn, $query);

    echo "<script>window.location.href='../userCart.php#" . $id . "';</script>";
    exit();
}
