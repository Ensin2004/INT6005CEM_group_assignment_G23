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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $ListName = htmlspecialchars($_POST["myListName"]);
    $CPU = htmlspecialchars($_POST["CPU"]);
    $Memory = htmlspecialchars($_POST["Memory"]);
    $MotherBoard = htmlspecialchars($_POST["MotherBoard"]);
    $Storage = htmlspecialchars($_POST["Storage"]);
    $GPU = htmlspecialchars($_POST["GPU"]);
    $PSU = htmlspecialchars($_POST["PSU"]);
    $id = $_SESSION['ID'];

    if (!$conn) {
        die("Database connection failed");
    } else {
        $query = "INSERT INTO mylist (list_name, user_id, cpu_id, memory_id, motherboard_id, storage_id, gpu_id, psu_id) 
                  VALUES ('$ListName', $id, '$CPU', '$Memory', '$MotherBoard', '$Storage', '$GPU', '$PSU');";

        if (mysqli_query($conn, $query)) {
            $ListId = mysqli_insert_id($conn);

            //calculate total price
            $itemIdArray = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mylist WHERE id = $ListId"));

            $CPUPrice = (float)mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM items WHERE id = {$itemIdArray['cpu_id']}"))["price"];
            $MemoryPrice = (float)mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM items WHERE id = {$itemIdArray['memory_id']}"))["price"];
            $MotherBoardPrice = (float)mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM items WHERE id = {$itemIdArray['motherboard_id']}"))["price"];
            $StoragePrice = (float)mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM items WHERE id = {$itemIdArray['storage_id']}"))["price"];
            $GPUPrice = (float)mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM items WHERE id = {$itemIdArray['gpu_id']}"))["price"];
            $PSUPrice = (float)mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM items WHERE id = {$itemIdArray['psu_id']}"))["price"];

            $totalPrice = number_format($CPUPrice + $MemoryPrice + $MotherBoardPrice + $StoragePrice + $GPUPrice + $PSUPrice, 2, ".", "");

            $addPriceQuery = "UPDATE mylist SET total_price = $totalPrice WHERE id = $ListId;";

            if (mysqli_query($conn, $addPriceQuery)) {
                echo "<script>alert('List added successfully'); window.location.href='../mylist.php';</script>";
            } else {
                echo "<script>alert('List added unsuccessful'); window.history.go(-1);</script>";
            }
        } else {
            echo "<script>alert('List added unsuccessful'); window.history.go(-1);</script>";
        }
    }
} else {
    echo "<script>alert('List added unsuccessful'); window.history.go(-1)</script>";
}
