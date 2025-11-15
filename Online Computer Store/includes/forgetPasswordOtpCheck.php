<?php
require_once "dbh.inc.php";
require_once "csrf.php";
require_once "crypto.php";

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

    // Collect form data
    $email = htmlspecialchars(trim($_POST["UserEmail"]));
    $password = password_hash($_POST["newPassword"], PASSWORD_ARGON2ID, $ARGON_OPTS);
    $otp =  htmlspecialchars(trim($_POST["otp"]));
    $otpVerify =  htmlspecialchars(trim($_POST["otp_inp"]));
    $expiresAt  = (int)($_POST['otp_expires_at']);

    if (!$expiresAt || time() > $expiresAt) {
        echo "<script>alert('OTP expired (1 minutes). Please resend a new OTP.'); window.history.back();</script>";
        exit;
    }

    if ($otpVerify != $otp) {
        echo "<script>alert('Wrong OTP'); window.history.back();</script>";
    } 
    else {
        // Find user id by decrypted email / secondary email
        $userId = null;
        $res = mysqli_query($conn, "SELECT id, email, secondary_email FROM users");
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $e1 = decrypt_field($row['email']);
                $e2 = decrypt_field($row['secondary_email']);
                if (strcasecmp($e1, $email) === 0 || strcasecmp($e2, $email) === 0) {
                    $userId = (int)$row['id'];
                    break;
                }
            }
        }

        if ($userId === null) {
            echo "<script>alert('Account not found for this email'); window.location.href='../login.php';</script>";
            exit;
        }

        $stmt = $conn->prepare("UPDATE users SET pwd = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $password, $userId);
            $stmt->execute();
            $stmt->close();
        }

        echo "<script>alert('Password Changed successfully'); window.location.href='../login.php';</script>";
    }
}
