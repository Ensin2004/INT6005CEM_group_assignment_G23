<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();
include "dbh.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $selected_items = $_POST['item_id'];
    $method = $_POST['payment'];
    $id = $_POST['user'];
    var_dump($_POST);
   

    
}
?>