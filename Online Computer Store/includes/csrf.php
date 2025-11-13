<?php 

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create new CSRF token if not set or expired
function createCSRFToken() {

    if (!isset($_SESSION['CSRFToken']) || !isset($_SESSION['CSRFTokenExpiry']) || time() > $_SESSION['CSRFTokenExpiry']) {
        $_SESSION['CSRFToken'] = bin2hex(random_bytes(32));
        $_SESSION['CSRFTokenExpiry'] = time() + 1800;
    }

    return $_SESSION['CSRFToken'];
}

// Check CSRF token
function checkCSRFToken($token) {

    // Ensure all variables are set
    if (!isset($_SESSION['CSRFToken']) || !isset($_SESSION['CSRFTokenExpiry']) || !isset($token)) {
        return false;
    }

    // Check CSRF token expiry
    if (time() > $_SESSION['CSRFTokenExpiry']) {
        unset($_SESSION['CSRFToken']);
        unset($_SESSION['CSRFTokenExpiry']);
        return false;
    }

    // Check CSRF token validity
    if (hash_equals($_SESSION['CSRFToken'], $token)) {
        unset($_SESSION['CSRFToken']);
        unset($_SESSION['CSRFTokenExpiry']);
        return true;
    } else {
        return false;
    }
}

// Create hidden input for CSRF token
function createCSRFInput() {
    $token = createCSRFToken();
    echo '<input type="hidden" name="csrfToken" value="' . htmlspecialchars($token) . '">';
}

?>