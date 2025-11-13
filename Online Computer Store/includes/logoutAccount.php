<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();

// 1. Clear all session variables
$_SESSION = [];

// 2. Delete the session cookie from browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// 3. Destroy the session on the server
session_destroy();

// 4. Redirect with alert
echo "<script>alert('Log out successfully'); window.location.href='../index.php';</script>";
exit;
?>
