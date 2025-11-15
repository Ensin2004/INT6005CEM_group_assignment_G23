<?php
require_once "dbh.inc.php";

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
function handleErrorAndExit($message = 'Unexpected error during sign up.') {
    error_log('[SIGNUP ERROR] ' . $message);
    http_response_code(500);
    header("Location: ../errors/500.php");
    exit;
}

try {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = htmlspecialchars(trim($_POST["newUsername"]));
        $email = htmlspecialchars(trim($_POST["newEmail"]));
        $phone = htmlspecialchars(trim($_POST["newPhone"]));
        $address = htmlspecialchars(trim($_POST["newAddress"]));
        $password = password_hash($_POST["newPassword"], PASSWORD_ARGON2ID, $ARGON_OPTS);
        $confirmPassword = $_POST["confirmPassword"];
        $otp =  htmlspecialchars(trim($_POST["otp"]));
        $otpVerify =  htmlspecialchars(trim($_POST["otp_inp"]));
        $expiresAt  = (int)($_POST['otp_expires_at']);

        if (!$expiresAt || time() > $expiresAt) {
            echo "<script>alert('OTP expired (1 minutes). Please resend a new OTP.'); window.history.back();</script>";
            exit;
        }

        if ($otpVerify != $otp) {
            echo "<script>alert('Wrong OTP'); window.history.back();</script>";
        } else {

            // Check DB connection before query
            if (!$conn) {
                handleErrorAndExit('Database connection failed.');
            }

            // Your original query
            $query = "INSERT INTO users (user_name, email, phone, user_address, pwd) VALUES ('$name', '$email', '$phone', '$address', '$password');";

            $result = mysqli_query($conn, $query);

            // If query fails unexpectedly → Error 500 page
            if (!$result) {
                handleErrorAndExit('Failed to execute INSERT query.');
            }

            echo "<script>alert('Sign up successfully'); window.location.href='../login.php';</script>";
        }
    }

} catch (Throwable $e) {
    // Catch any unexpected PHP error/exception
    handleErrorAndExit($e->getMessage());
}
