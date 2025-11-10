<?php
require_once "dbh.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $email = htmlspecialchars($_POST["AdminEmail"]);
    $password = htmlspecialchars($_POST["confirmPassword"]);
    $otp = htmlspecialchars($_POST["otp"]);
    $otpVerify = htmlspecialchars($_POST["otp_inp"]);

    // Compare OTP
    if ($otpVerify != $otp) {
        echo "<script>alert('Wrong OTP'); window.history.back();</script>";
        exit;
    } else {
        // ✅ Securely hash the new password before saving
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update admin password
        $query = "UPDATE admins SET admin_pwd = '$hashedPassword' WHERE admin_email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>alert('Admin password changed successfully!'); window.location.href='../index.php';</script>";
        } else {
            echo "<script>alert('Error updating password'); window.history.back();</script>";
        }
    }
}
?>
