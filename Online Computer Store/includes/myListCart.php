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

if (isset($_GET['id'])) {
    $mylistid = $_GET['id'];

    $id = $_SESSION['ID'];

    $result = mysqli_query($conn, "SELECT mylist.id,mylist.list_name,mylist.user_id,cpu.item_name AS cpu_name, cpu.price AS cpu_price,motherboard.item_name AS motherboard_name, motherboard.price AS motherboard_price,memory.item_name AS memory_name, memory.price AS memory_price,
        gpu.item_name AS gpu_name, gpu.price AS gpu_price,psu.item_name AS psu_name, psu.price AS psu_price,storage.item_name AS storage_name, storage.price AS storage_price
        FROM mylist
        JOIN items AS cpu ON mylist.cpu_id = cpu.id
        JOIN items AS motherboard ON mylist.motherboard_id = motherboard.id
        JOIN items AS memory ON mylist.memory_id = memory.id
        JOIN items AS gpu ON mylist.gpu_id = gpu.id
        JOIN items AS psu ON mylist.psu_id = psu.id
        JOIN items AS storage ON mylist.storage_id = storage.id
        WHERE 
        mylist.id = $mylistid;");
    $row = mysqli_fetch_assoc($result);

    $CPU = $row["cpu_name"];
    $Memory = $row["memory_name"];
    $MotherBoard = $row["motherboard_name"];
    $Storage = $row["storage_name"];
    $GPU = $row["gpu_name"];
    $PSU = $row["psu_name"];

    $CPUs = mysqli_query($conn, "SELECT * FROM items WHERE item_name='$CPU'");
    $Memorys = mysqli_query($conn, "SELECT * FROM items WHERE item_name='$Memory'");
    $MotherBoards = mysqli_query($conn, "SELECT * FROM items WHERE item_name='$MotherBoard'");
    $Storages = mysqli_query($conn, "SELECT * FROM items WHERE item_name='$Storage'");
    $GPUs = mysqli_query($conn, "SELECT * FROM items WHERE item_name='$GPU'");
    $PSUs = mysqli_query($conn, "SELECT * FROM items WHERE item_name='$PSU'");

    $cpu = mysqli_fetch_assoc($CPUs);
    $memory = mysqli_fetch_assoc($Memorys);
    $motherboard = mysqli_fetch_assoc($MotherBoards);
    $storage = mysqli_fetch_assoc($Storages);
    $gpu = mysqli_fetch_assoc($GPUs);
    $psu = mysqli_fetch_assoc($PSUs);

    $item_qty = 1;

    if (!$conn) {
        die("Database connection failed");
    } else {
        echo "Database connection successful";
    }

    function checkAndUpdateCart($conn, $item_id, $user_id, $item_qty)
    {
        $check_query = "SELECT * FROM cart WHERE item_id = $item_id AND user_id = $user_id";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {

            $update_query = "UPDATE cart SET qty = qty + $item_qty WHERE item_id = $item_id AND user_id = $user_id";
            mysqli_query($conn, $update_query);
        } else {

            $insert_query = "INSERT INTO cart (qty, item_id, user_id) VALUES ($item_qty, $item_id, $user_id)";
            mysqli_query($conn, $insert_query);
        }
    }

    checkAndUpdateCart($conn, $cpu['id'], $id, $item_qty);
    checkAndUpdateCart($conn, $memory['id'], $id, $item_qty);
    checkAndUpdateCart($conn, $motherboard['id'], $id, $item_qty);
    checkAndUpdateCart($conn, $storage['id'], $id, $item_qty);
    checkAndUpdateCart($conn, $gpu['id'], $id, $item_qty);
    checkAndUpdateCart($conn, $psu['id'], $id, $item_qty);

    echo "<script>alert('Added to cart successfully'); window.location.href='../mylist.php';</script>";
} else {
    header("Location: ../customizePC.php");
}