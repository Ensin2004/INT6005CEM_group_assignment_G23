<?php
session_start();
require_once "dbh.inc.php";
require_once "csrf.php";

$ARGON_OPTS = [
    'memory_cost' => 131072, // 128 MB
    'time_cost'   => 3,      // 3 iterations
    'threads'     => 1
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check CSRF token
    if (!isset($_POST['csrfToken']) || !checkCSRFToken($_POST['csrfToken'])) {
        die("<script> alert('Invalid or expired CSRF token. Please refresh the page and try again.'); window.history.go(-1); </script>");
    }

    $name = htmlspecialchars($_POST["newAdminName"]);
    $email = htmlspecialchars($_POST["newAdminEmail"]);
    $password = htmlspecialchars($_POST["newAdminPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
    $img = $_FILES["adminimg"];

    if ($confirmPassword != $password) {
        echo "<script>alert('Confirm password does not match'); window.history.back();</script>";
        exit();
    }

    $id = $_SESSION['ID'];

    // If admin typed a new password, validate + hash (Argon2id)
    if (strlen($password) > 0 || strlen($confirmPassword) > 0) {

        if ($password !== $confirmPassword) {
            echo "<script>alert('Confirm password does not match'); window.history.back();</script>";
            exit;
        }

        // Password Requirements:
        // 8–20 chars, at least one digit, at least one special char (@$!%*?&)
        $pattern = "/^(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z0-9@$!%*?&]{8,20}$/";

        if (!preg_match($pattern, $password)) {
            echo "<script>
                alert('Password must be 8–20 characters, contain at least one number and one special character (@$!%*?&)');
                window.history.back();
            </script>";
            exit;
        }

        // Hash new password using Argon2id
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID, $ARGON_OPTS);
    }


    $oriImgPath = mysqli_fetch_assoc(mysqli_query($conn, "SELECT admin_image FROM admins WHERE id = $id;"));
    $query = "UPDATE admins SET admin_name='$name', admin_email='$email' WHERE id = '$id'";

    // Only update password if user actually typed one
    if (!empty($password)) {
        mysqli_query($conn, "UPDATE admins SET admin_pwd='$passwordHash' WHERE id='$id'");
    }

    if (mysqli_query($conn, $query)) {
        if (!empty($img["name"])) {
            $img_file_name = uniqid("admin_", true) . "." . pathinfo($img["name"], PATHINFO_EXTENSION);
            $query = "UPDATE admins SET admin_image = '$img_file_name' WHERE id = $id;";
            
            if (mysqli_query($conn, $query)) {
                move_uploaded_file($img["tmp_name"], "../Image/" . $img_file_name);

                //Delete the old image only if it's NOT the default
                if (!empty($oriImgPath["admin_image"]) && $oriImgPath["admin_image"] != "no_profile_pic.png") {
                    $file = "../Image/" . $oriImgPath["admin_image"];
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }

        echo "<script>alert('Admin account updated successfully'); window.location.href='../adminAccount.php';</script>";
    } else {
        echo "<script>alert('Failed to update admin account'); window.location.href='../editAdminAccount.php';</script>";
    }
}
?>
