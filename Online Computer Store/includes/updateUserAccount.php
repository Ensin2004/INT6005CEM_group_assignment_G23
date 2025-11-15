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
require_once "crypto.php";

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

        // Check CSRF token
        if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
            echo "<script>alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1);</script>";
            exit;
        }

        // Database connection
        if (!$conn) {
            handleErrorAndExit('Database connection failed.');
        } else {

            // Collect form data
            $name           = htmlspecialchars(trim($_POST["newUsername"]));
            $email          = htmlspecialchars(trim($_POST["newEmail"]));
            $phone          = htmlspecialchars(trim($_POST["newPhone"]));
            $address        = htmlspecialchars(trim($_POST["newAddress"]));
            $password       = $_POST["newPassword"] ?? '';
            $confirmPassword= $_POST["confirmPassword"] ?? '';
            $img1           = $_FILES["accountimg"];
            $id             = $_SESSION['ID'];

            // If user typed a new password, validate + hash (Argon2id)
            if (strlen($password) > 0 || strlen($confirmPassword) > 0) {

                if ($password !== $confirmPassword) {
                    echo "<script>alert('Confirm password does not match'); window.history.back();</script>";
                    exit;
                }

                // Hash new password using Argon2id
                $passwordHash = password_hash($password, PASSWORD_ARGON2ID, $ARGON_OPTS);
                if ($passwordHash === false) {
                    handleErrorAndExit('Password hashing failed.');
                }
            }

            // Get original image path
            $oriResult = mysqli_query($conn, "SELECT user_image FROM users WHERE id = $id;");
            if (!$oriResult) {
                handleErrorAndExit('Failed to fetch existing user image.');
            }
            $oriImgPath = mysqli_fetch_assoc($oriResult);

            // Encrypt PII
            $enc_name    = encrypt_field($name);
            $enc_phone   = encrypt_field($phone);
            $enc_address = encrypt_field($address);

            // Update basic info (no email change here)
            $stmt = $conn->prepare("UPDATE users SET user_name = ?, phone = ?, user_address = ? WHERE id = ?");
            if (!$stmt) {
                handleErrorAndExit('Prepare failed: ' . $conn->error);
            }
            $stmt->bind_param("sssi", $enc_name, $enc_phone, $enc_address, $id);

            if (!$stmt->execute()) {
                handleErrorAndExit('Failed to update user profile details.');
            }
            $stmt->close();

            // Only update password if user actually typed one
            if (!empty($password)) {
                $pwdUpdate = mysqli_query($conn, "UPDATE users SET pwd='$passwordHash' WHERE id='$id'");
                if (!$pwdUpdate) {
                    handleErrorAndExit('Failed to update user password.');
                }
            }

            // Update image if there are changes
            if (!empty($img1["name"])) {
                $img1_file_name = uniqid("", true) . "." . pathinfo($img1["name"], PATHINFO_EXTENSION);
                $imgQuery = "UPDATE users SET user_image = '$img1_file_name' WHERE id = $id;";

                $imgUpdate = mysqli_query($conn, $imgQuery);
                if ($imgUpdate) {
                    // Move new file
                    move_uploaded_file($img1["tmp_name"], "../image/" . $img1_file_name);

                    // Delete old file if exists
                    // NOTE: column is user_image, not accountimg
                    if (!empty($oriImgPath["user_image"])) {
                        $file = "../image/" . $oriImgPath["user_image"];
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                } else {
                    // Application-level failure (no sensitive details)
                    echo "<script>alert('Account updated unsuccessful'); window.location.href='../editAccountForm.php';</script>";
                    exit;
                }
            }

            // All done
            echo "<script>alert('Account update successfully'); window.location.href='../accountPage.php';</script>";
        }
    }

} catch (Throwable $e) {
    // Catch any unexpected PHP error/exception (including mysqli exceptions if enabled)
    handleErrorAndExit($e->getMessage());
}
?>
