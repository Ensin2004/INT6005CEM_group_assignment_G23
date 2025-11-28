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
    header('Location: login.php');
    exit();
}

/**
 * Basic sanitization only:
 * - trim
 * - strip HTML tags
 * - remove special symbols (keeps letters/numbers/space/-/_/./,)
 */
function sanitize_basic(?string $s): string {
    if ($s === null) return '';
    $s = trim(strip_tags($s));
    // allow: a-z, A-Z, 0-9, space, dash, underscore, dot, comma
    $s = preg_replace('/[^a-zA-Z0-9 \-\_\.\,]/u', '', $s);
    // collapse multiple spaces
    $s = preg_replace('/\s+/u', ' ', $s);
    return $s;
}

/**
 * Escaping helper for legacy dynamic SQL (not needed for prepared statements).
 * Use only if you absolutely must concatenate into SQL.
 */
function escape_sql(mysqli $conn, string $s): string {
    return mysqli_real_escape_string($conn, $s);
}