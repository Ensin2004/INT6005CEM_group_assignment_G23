<?php
// Start session only if not already active (prevents warnings)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,       // expires when browser closes
        'path'     => '/',
        'secure'   => true,    // only over HTTPS
        'httponly' => true,    // JS cannot access it
        'samesite' => 'Strict' // strong CSRF protection
    ]);
    session_start();
}

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

/**
 * Basic sanitization only (no validation here):
 * - trim
 * - strip HTML tags
 * - remove special symbols (keeps letters/numbers/space/-/_/./,)
 */
function sanitize_basic(?string $s): string {
    if ($s === null) return '';

    $s = trim($s);                  // remove spaces at start/end
    $s = strip_tags($s);            // remove any HTML tags
    // allow a–z, A–Z, 0–9, space, dash, underscore, dot, comma
    $s = preg_replace(pattern: '/[^a-zA-Z0-9 \-_\.,]/u', replacement: '', subject: $s);
    // collapse multiple spaces
    $s = preg_replace(pattern: '/\s+/u', replacement: ' ', subject: $s);

    return $s;
}


/**
 * Escaping helper for legacy dynamic SQL (not needed for prepared statements)
 */
function escape_sql(mysqli $conn, string $s): string {
    return mysqli_real_escape_string($conn, $s);
}

/**
 * Output-encoding helpers (XSS mitigation by context)
 */

function e(?string $s): string {            // HTML text/attribute
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function q(string $s): string {             // URL query parameter
    return urlencode($s);
}

function qp(string $s): string {            // URL path segment
    return rawurlencode($s);
}

