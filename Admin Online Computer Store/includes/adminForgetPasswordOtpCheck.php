<?php
require_once "dbh.inc.php";

$ARGON_OPTS = [
    'memory_cost' => 131072, // 128 MB
    'time_cost'   => 3,      // 3 iterations
    'threads'     => 1
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $email = htmlspecialchars(trim($_POST["AdminEmail"]));
    $password = password_hash($_POST["newPassword"], PASSWORD_ARGON2ID, $ARGON_OPTS);
    $otp = htmlspecialchars(trim($_POST["otp"]));
    $otpVerify = htmlspecialchars(trim($_POST["otp_inp"]));

    // Compare OTP
    if ($otpVerify != $otp) {
        echo "<script>alert('Wrong OTP'); window.history.back();</script>";
        exit;
    } else {
        // Update admin password
        $query = "UPDATE admins SET admin_pwd = '$password' WHERE admin_email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>alert('Admin password changed successfully!'); window.location.href='../index.php';</script>";
        } else {
            echo "<script>alert('Error updating password'); window.history.back();</script>";
        }
    }
}
?>
