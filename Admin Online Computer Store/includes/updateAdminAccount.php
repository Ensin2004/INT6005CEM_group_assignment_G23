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
require_once "csrf.php";

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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check CSRF token (treat as user error, not 500)
        if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
            echo "<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>";
            exit;
        }

        // Check DB connection
        if (!$conn) {
            handleErrorAndExit('Database connection failed.');
        }

        $name            = htmlspecialchars(trim($_POST["newAdminName"]));
        $email           = htmlspecialchars(trim($_POST["newAdminEmail"]));
        $password        = $_POST["newAdminPassword"];
        $confirmPassword = $_POST["confirmPassword"];
        $img             = $_FILES["adminimg"];
        $id              = $_SESSION['ID'];

        // If admin typed a new password, validate + hash (Argon2id)
        if (strlen($password) > 0 || strlen($confirmPassword) > 0) {

            if ($password !== $confirmPassword) {
                echo "<script>alert('Confirm password does not match'); window.history.back();</script>";
                exit;
            }

            $passwordHash = password_hash($password, PASSWORD_ARGON2ID, $ARGON_OPTS);
            if ($passwordHash === false) {
                handleErrorAndExit('Password hashing failed.');
            }
        }

        // Get current image path
        $oriResult = mysqli_query($conn, "SELECT admin_image FROM admins WHERE id = $id;");
        if (!$oriResult) {
            handleErrorAndExit('Failed to fetch existing admin image.');
        }
        $oriImgPath = mysqli_fetch_assoc($oriResult);

        // Update basic info
        $query = "UPDATE x SET admin_name='$name', admin_email='$email' WHERE id = '$id'";
        $updateInfo = mysqli_query($conn, $query);

        if (!$updateInfo) {
            handleErrorAndExit('Failed to update admin account details.');
        }

        // Only update password if admin actually typed one
        if (!empty($password)) {
            $pwdUpdate = mysqli_query($conn, "UPDATE admins SET admin_pwd='$passwordHash' WHERE id='$id'");
            if (!$pwdUpdate) {
                handleErrorAndExit('Failed to update admin password.');
            }
        }

        // Handle image upload if there is a new image
        if (!empty($img["name"])) {
            $img_file_name = uniqid("admin_", true) . "." . pathinfo($img["name"], PATHINFO_EXTENSION);
            $imgQuery = "UPDATE admins SET admin_image = '$img_file_name' WHERE id = $id;";

            $imgUpdate = mysqli_query($conn, $imgQuery);
            if ($imgUpdate) {
                move_uploaded_file($img["tmp_name"], "../Image/" . $img_file_name);

                // Delete the old image only if it's NOT the default
                if (!empty($oriImgPath["admin_image"]) && $oriImgPath["admin_image"] != "no_profile_pic.png") {
                    $file = "../Image/" . $oriImgPath["admin_image"];
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            } else {
                // app-level failure, no sensitive info
                echo "<script>alert('Failed to update admin account'); window.location.href='../editAdminAccount.php';</script>";
                exit;
            }
        }

        echo "<script>alert('Admin account updated successfully'); window.location.href='../adminAccount.php';</script>";
        exit;
    }

} catch (Throwable $e) {
    // Any unexpected PHP / mysqli error
    handleErrorAndExit($e->getMessage());
}
?>
