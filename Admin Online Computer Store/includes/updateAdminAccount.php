<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();
require_once "dbh.inc.php";
require_once "csrf.php";
require_once "audit.php";

$ARGON_OPTS = [
    'memory_cost' => 131072,
    'time_cost'   => 3,
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

        // ---- Collect input --------------------------------------------------
        $name            = htmlspecialchars(trim($_POST["newAdminName"]));
        $email           = htmlspecialchars(trim($_POST["newAdminEmail"]));
        $password        = $_POST["newAdminPassword"];
        $confirmPassword = $_POST["confirmPassword"];
        $img             = $_FILES["adminimg"] ?? null;
        $id              = (int)($_SESSION['ID'] ?? 0);

        if ($id <= 0) {
            handleErrorAndExit('Missing or invalid admin ID in session.');
        }

        // BEFORE snapshot -------------- AUDIT
        $beforeResult = mysqli_query(
            $conn,
            "SELECT admin_name, admin_email, admin_image FROM admins WHERE id = {$id}"
        );
        if (!$beforeResult) {
            handleErrorAndExit('Failed to fetch existing admin data.');
        }

        $before = mysqli_fetch_assoc($beforeResult) ?: [];
        $oriImgPath = [
            "admin_image" => $before["admin_image"] ?? null
        ];

        $password_changed = false;
        $passwordHash     = null;
        $ok               = true;
        $newImageFile     = null;

        // ---- Password handling (Argon2id) ----------------------------------
        if (strlen($password) > 0 || strlen($confirmPassword) > 0) {

            if ($password !== $confirmPassword) {
                // AUDIT: failed attempt to change password / profile
                audit_log(
                    $conn,
                    $_SESSION['ID'] ?? null,
                    $_SESSION['role'] ?? null,
                    'admin_update',
                    'admins',
                    $id,
                    'Profile update failed: confirm password mismatch',
                    $before,
                    null,
                    'failure'
                );

                echo "<script>alert('Confirm password does not match'); window.history.back();</script>";
                exit;
            }

            $passwordHash = password_hash($password, PASSWORD_ARGON2ID, $ARGON_OPTS);
            if ($passwordHash === false) {
                handleErrorAndExit('Password hashing failed.');
            }

            $password_changed = true;
        }

        // ---- Update basic info (name, email) -------------------------------
        $query = "UPDATE admins 
                  SET admin_name='{$name}', admin_email='{$email}' 
                  WHERE id = '{$id}'";

        if (!mysqli_query($conn, $query)) {
            $ok = false;
        }

        // ---- Update password only if user actually typed one ---------------
        if ($ok && $password_changed) {
            if (!mysqli_query(
                $conn,
                "UPDATE admins SET admin_pwd='{$passwordHash}' WHERE id='{$id}'"
            )) {
                $ok = false;
            }
        }

        // ---- Image upload ---------------------------------------------------
        if ($ok && $img && !empty($img["name"])) {
            $img_file_name = uniqid("admin_", true) . "." . pathinfo($img["name"], PATHINFO_EXTENSION);
            $imgQuery = "UPDATE admins SET admin_image = '{$img_file_name}' WHERE id = {$id};";

            if (mysqli_query($conn, $imgQuery)) {
                if (move_uploaded_file($img["tmp_name"], "../Image/" . $img_file_name)) {

                    // Delete the old image only if it's NOT the default
                    if (!empty($oriImgPath["admin_image"]) && $oriImgPath["admin_image"] != "no_profile_pic.png") {
                        $file = "../Image/" . $oriImgPath["admin_image"];
                        if (file_exists($file)) {
                            @unlink($file);
                        }
                    }

                    $newImageFile = $img_file_name;
                } else {
                    // File move failed – mark as failure (DB already updated)
                    $ok = false;
                }
            } else {
                $ok = false;
            }
        }

        // AFTER snapshot --------------- AUDIT
        $after = $before;
        $after['admin_name']  = $name;
        $after['admin_email'] = $email;
        if ($newImageFile)         $after['admin_image']      = $newImageFile;
        if ($password_changed)     $after['password_changed'] = true;  // never log hashes

        audit_log(
            $conn,
            $_SESSION['ID'] ?? null,
            $_SESSION['role'] ?? null,
            'admin_update',
            'admins',
            $id,
            $ok ? 'Updated own admin profile' : 'Failed to update admin profile',
            $before,
            $after,
            $ok ? 'success' : 'failure'
        );

        if ($ok) {
            echo "<script>alert('Admin account updated successfully'); window.location.href='../adminAccount.php';</script>";
            exit;
        } else {
            // app-level failure, no sensitive info
            echo "<script>alert('Failed to update admin account'); window.location.href='../editAdminAccount.php';</script>";
            exit;
        }
    }

} catch (Throwable $e) {
    // Any unexpected PHP / mysqli error
    handleErrorAndExit($e->getMessage());
}
?>
