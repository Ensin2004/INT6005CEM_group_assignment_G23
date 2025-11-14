<?php
require_once "dbh.inc.php";
require_once "csrf.php";
require_once "audit.php";

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
            $adminRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, role FROM admins WHERE admin_email = '".mysqli_real_escape_string($conn,$email)."' LIMIT 1"));
            audit_log($conn,
                      $adminRow['id'] ?? null, $adminRow['role'] ?? null,
                      'password_reset','admins', $adminRow['id'] ?? null,
                      "Password reset via OTP for {$email}");
            echo "<script>alert('Admin password changed successfully!'); window.location.href='../index.php';</script>";
        } else {
            echo "<script>alert('Error updating password'); window.history.back();</script>";
        }

    }
}
?>
