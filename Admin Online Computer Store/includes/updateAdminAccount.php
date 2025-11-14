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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check CSRF token
    if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
        die("<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>");
    }

    $name = htmlspecialchars(trim($_POST["newAdminName"]));
    $email = htmlspecialchars(trim($_POST["newAdminEmail"]));
    $password = $_POST["newAdminPassword"];
    $confirmPassword = $_POST["confirmPassword"];
    $img = $_FILES["adminimg"];

    $id = (int)($_SESSION['ID'] ?? 0);

    // BEFORE snapshot -------------- AUDIT
    $before = mysqli_fetch_assoc(mysqli_query($conn, "SELECT admin_name, admin_email, admin_image FROM admins WHERE id = {$id}")) ?: [];

    $password_changed = false;

    // If admin typed a new password, validate + hash (Argon2id)
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
        $password_changed = true;
    }

    $oriImgPath = mysqli_fetch_assoc(mysqli_query($conn, "SELECT admin_image FROM admins WHERE id = {$id};"));
    $query = "UPDATE admins SET admin_name='{$name}', admin_email='{$email}' WHERE id = '{$id}'";

    $ok = true;
    if (!mysqli_query($conn, $query)) {
        $ok = false;
    }

    // Only update password if user actually typed one
    if ($ok && !empty($password_changed)) {
        if (!mysqli_query($conn, "UPDATE admins SET admin_pwd='{$passwordHash}' WHERE id='{$id}'")) {
            $ok = false;
        }
    }

    $newImageFile = null;
    if ($ok && !empty($img["name"])) {
        $img_file_name = uniqid("admin_", true) . "." . pathinfo($img["name"], PATHINFO_EXTENSION);
        $query = "UPDATE admins SET admin_image = '{$img_file_name}' WHERE id = {$id};";
        if (mysqli_query($conn, $query)) {
            if (move_uploaded_file($img["tmp_name"], "../Image/" . $img_file_name)) {
                // Delete the old image only if it's NOT the default
                if (!empty($oriImgPath["admin_image"]) && $oriImgPath["admin_image"] != "no_profile_pic.png") {
                    $file = "../Image/" . $oriImgPath["admin_image"];
                    if (file_exists($file)) { @unlink($file); }
                }
                $newImageFile = $img_file_name;
            } else {
                // If file move fails, revert DB image change?
                // For now just mark as failure in audit (DB still points to new file).
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
    } else {
        echo "<script>alert('Failed to update admin account'); window.location.href='../editAdminAccount.php';</script>";
    }
}
?>
