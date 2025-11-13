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

$ARGON_OPTS = [
    'memory_cost' => 131072, // 128 MB
    'time_cost'   => 3,      // 3 iterations
    'threads'     => 1
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars(trim($_POST["newUsername"]));
    $email = htmlspecialchars(trim($_POST["newEmail"]));
    $phone = htmlspecialchars(trim($_POST["newPhone"]));
    $address = htmlspecialchars(trim($_POST["newAddress"]));
    $password = $_POST["newPassword"] ?? '';
    $confirmPassword = $_POST["confirmPassword"] ?? '';
    $img1 = $_FILES["accountimg"];

    // Database connection
    if (!$conn) {
        die("Database connection failed");
    }

    else{

    // Prepare SQL statement to prevent SQL injection
    $id = $_SESSION['ID'];

    // If user typed a new password, validate + hash (Argon2id)
    if (strlen($password) > 0 || strlen($confirmPassword) > 0) {

        if ($password !== $confirmPassword) {
            echo "<script>alert('Confirm password does not match'); window.history.back();</script>";
            exit;
        }

        // Hash new password using Argon2id
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID, $ARGON_OPTS);
    }

    $oriImgPath = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_image FROM users WHERE id = $id;"));
    $query = "UPDATE users SET user_name='$name', email='$email', phone='$phone', user_Address='$address' WHERE id = '$id'";

    // Only update password if user actually typed one
    if (!empty($password)) {
        mysqli_query($conn, "UPDATE users SET pwd='$passwordHash' WHERE id='$id'");
    }

    if (mysqli_query($conn, $query)) {
        // Update image if there are changes
        if (!empty($img1["name"])) {
            $img1_file_name = uniqid("", true) . "." . pathinfo($img1["name"], PATHINFO_EXTENSION);
            $query = "UPDATE users SET user_image = '$img1_file_name' WHERE id = $id;";
            if (mysqli_query($conn, $query)) {
                move_uploaded_file($img1["tmp_name"], "../image/" . $img1_file_name);
                if ($oriImgPath["accountimg"] != "") {
                    $file = "../image/" . $oriImgPath["accountimg"];
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            } else {
                echo "<script>alert('Account updated unsuccessful'); window.location.href='../editAccountForm.php';</script>";
            }
        }

        echo "<script>alert('Account update successfully'); window.location.href='../accountPage.php';</script>";
    } else {
        echo "<script>alert('Account update failed'); window.location.href='../editAccountForm.php';</script>";
    }
}
}
?>