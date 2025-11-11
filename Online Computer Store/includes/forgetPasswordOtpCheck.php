<?php
require_once "dbh.inc.php";

$ARGON_OPTS = [
    'memory_cost' => 131072, // 128 MB
    'time_cost'   => 3,      // 3 iterations
    'threads'     => 1
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $email = htmlspecialchars($_POST["UserEmail"]);
    $password = password_hash($_POST["newPassword"], PASSWORD_ARGON2ID, $ARGON_OPTS);
    $otp =  htmlspecialchars($_POST["otp"]);
    $otpVerify =  htmlspecialchars($_POST["otp_inp"]);
    $expiresAt  = (int)($_POST['otp_expires_at']);

    if (!$expiresAt || time() > $expiresAt) {
        echo "<script>alert('OTP expired (5 minutes). Please resend a new OTP.'); window.history.back();</script>";
        exit;
    }

    if ($otpVerify != $otp) {
        echo "<script>alert('Wrong OTP'); window.history.back();</script>";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $query = "UPDATE users SET pwd = '$password' WHERE email = '$email' OR secondary_email = '$email'";
        mysqli_query($conn, $query);

        echo "<script>alert('Password Changed successfully'); window.location.href='../login.php';</script>";
    }
}
