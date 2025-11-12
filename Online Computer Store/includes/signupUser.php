<?php
require_once "dbh.inc.php";

$ARGON_OPTS = [
    'memory_cost' => 131072, // 128 MB
    'time_cost'   => 3,      // 3 iterations
    'threads'     => 1
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    //Original
    // $name = htmlspecialchars($_POST["newUsername"]);
    // $email = htmlspecialchars($_POST["newEmail"]);
    // $phone = htmlspecialchars($_POST["newPhone"]);
    // $address = htmlspecialchars($_POST["newAddress"]);

    $name = trim($_POST["newUsername"]);
    $email = trim($_POST["newEmail"]);
    $phone = trim($_POST["newPhone"]);
    $address = trim($_POST["newAddress"]);
    $password = password_hash($_POST["newPassword"], PASSWORD_ARGON2ID, $ARGON_OPTS);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
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
        $query = "INSERT INTO users (user_name, email, phone, user_address, pwd) VALUES ('$name', '$email', '$phone', '$address', '$password');";

        $result =  mysqli_query($conn, $query);

        echo "<script>alert('Sign up successfully'); window.location.href='../login.php';</script>";
    }
}
