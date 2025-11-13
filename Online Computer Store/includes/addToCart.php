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
    $itemID = $_GET["item"];
    $quantity = $_GET["qty"];
    $userID = $_SESSION["ID"];

    function checkAndUpdateCart($conn, $item_id, $qty, $user_id)
    {
        $check_query = "SELECT * FROM cart WHERE item_id = $item_id AND user_id = $user_id";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $update_query = "UPDATE cart SET qty = qty + $qty WHERE item_id = $item_id AND user_id = $user_id";
            mysqli_query($conn, $update_query);
        } else {
            $insert_query = "INSERT INTO cart (qty, item_id, user_id) VALUES ($qty, $item_id, $user_id)";
            mysqli_query($conn, $insert_query);
        }
    }

    checkAndUpdateCart($conn, $itemID, $quantity, $userID);

    echo"<script> alert('Item added to cart'); window.history.go(-1); </script>";
}
