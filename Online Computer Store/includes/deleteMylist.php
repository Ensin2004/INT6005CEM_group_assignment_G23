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
    $id = $_GET['id'];

    $result = mysqli_query($conn, "DELETE FROM mylist WHERE id = '$id';");

    echo
    "<script>alert('Delete Successfully'); window.location.href='../mylist.php';</script>";
}
