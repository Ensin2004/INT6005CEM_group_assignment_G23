<?php
require_once "dbh.inc.php";
require_once "csrf.php";
require_once "audit.php";

$ARGON_OPTS = [
    'memory_cost' => 131072, // 128 MB
    'time_cost'   => 3,      // 3 iterations
    'threads'     => 1
];

/**
 * Generic error handler for this page
 * - Logs the error
 * - Sends ERROR 500 status
 * - Redirects to unified ERROR 500 page (no sensitive info to user)
 */
function handleErrorAndExit($message = 'Unexpected error during account update.') {
    error_log('[ACCOUNT UPDATE ERROR] ' . $message);
    http_response_code(500);
    header("Location: ../errors/500.php");
    exit;
}

try {

    // Must be POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        handleErrorAndExit("Invalid request method.");
    }

    // CSRF token check (user-level error)
    if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
        echo "<script>alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1);</script>";
        exit;
    }

    if (!isset($_POST['addManager'])) {
        handleErrorAndExit("addManager not set in form.");
    }

    // DB connection check
    if (!$conn) {
        handleErrorAndExit("Database connection failed.");
    }

    // Collect and sanitize data
    $name  = htmlspecialchars(trim($_POST['admin_name']));
    $email = htmlspecialchars(trim($_POST['admin_email']));
    $pwd   = $_POST['admin_pwd'];

    // Hash password
    $passwordHash = password_hash($pwd, PASSWORD_ARGON2ID, $ARGON_OPTS);
    if ($passwordHash === false) {
        handleErrorAndExit("Password hashing failed.");
    }

    // Default profile image
    $defaultImg = "no_profile_pic.png";

    // Insert query
    $sql = "INSERT INTO admins (admin_name, admin_email, admin_pwd, role, admin_image)
            VALUES ('$name', '$email', '$passwordHash', 'manager', '$defaultImg')";

    $insert = mysqli_query($conn, $sql);

    if (!$insert) {
        // unexpected DB error → go to 500 page
        handleErrorAndExit("Failed to insert manager into database.");
    }
  
    if (mysqli_query($conn, $sql)) {
        audit_log(
        $conn,
        $_SESSION['ID'], $_SESSION['role'] ?? null,
        'admin_create', 'admins', $conn->insert_id,
        "Created manager '{$name}' <{$email}>",
        null,
        ['admin_name'=>$name,'admin_email'=>$email,'role'=>'manager']
        );

        echo "<script>alert('Manager added successfully!'); window.location.href='../managers.php';</script>";
    } else {
        echo "<script>alert('Error adding manager: " . mysqli_error($conn) . "'); window.history.go(-1);</script>";
    }
  
    exit;

} catch (Throwable $e) {
    handleErrorAndExit($e->getMessage());
}
?>
