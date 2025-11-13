<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();

if(!isset($_SESSION['ID'])) {
    header('Location: index.php');
    exit();
}

// Session Timeout Check
$timeoutSeconds = 300;

if (isset($_SESSION['LastActivity']) && (time() - $_SESSION['LastActivity']) >= $timeoutSeconds) {
    echo "<script> window.location.href='includes/logoutAccount.php?timeout=1'; </script>";
    exit;
}

$_SESSION['LastActivity'] = time();