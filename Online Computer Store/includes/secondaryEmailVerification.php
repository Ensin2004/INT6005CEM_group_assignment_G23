<?php
require_once "dbh.inc.php";
require_once "crypto.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $firstEmail      = $_POST["primaryEmail"]     ?? '';
    $email           = $_POST["secondaryEmail"]   ?? '';
    $confirmPassword = $_POST["confirmPassword"]  ?? '';
    $otp             = $_POST["otp"]              ?? '';
    $otpVerify       = $_POST["otp_inp"]          ?? '';
    $expiresAt       = (int)($_POST["otp_expires_at"] ?? 0);

    // ---- OTP expired ----
    if (!$expiresAt || time() > $expiresAt) {
        echo '
<form id="back" method="post" action="../secondaryEmailPage2.php">
  <input type="hidden" name="primaryEmail"    value="'.htmlspecialchars($firstEmail, ENT_QUOTES).'">
  <input type="hidden" name="secondaryEmail"  value="'.htmlspecialchars($email, ENT_QUOTES).'">
  <input type="hidden" name="alert"           value="OTP expired. Please resend a new OTP.">
  <input type="hidden" name="no_autosend"     value="1"> 
</form>
<script>document.getElementById("back").submit();</script>';
        exit;
    }

    // ---- Wrong OTP -> auto-post back, keep values ----
    if ($otpVerify !== $otp) {
        echo '
<form id="back" method="post" action="../secondaryEmailPage2.php">
  <input type="hidden" name="primaryEmail"     value="'.htmlspecialchars($firstEmail, ENT_QUOTES).'">
  <input type="hidden" name="secondaryEmail"   value="'.htmlspecialchars($email, ENT_QUOTES).'">
  <input type="hidden" name="otp"              value="'.htmlspecialchars($otp, ENT_QUOTES).'">
  <input type="hidden" name="otp_expires_at"   value="'.htmlspecialchars($expiresAt, ENT_QUOTES).'">
  <input type="hidden" name="alert"            value="Wrong verify OTP">
</form>
<script>document.getElementById("back").submit();</script>';
        exit;
    }

    // -----------------------------------------------------------------
    // OTP correct  -> update encrypted secondary_email
    // -----------------------------------------------------------------

    if (!$conn) {
        die("Database connection failed");
    }

    // 1) Find user id by decrypted PRIMARY email ($firstEmail)
    $userId = null;
    $res = mysqli_query($conn, "SELECT id, email FROM users");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $dec_email = decrypt_field($row['email']);
            if (strcasecmp($dec_email, $firstEmail) === 0) {
                $userId = (int)$row['id'];
                break;
            }
        }
    }

    if ($userId === null) {
        echo "<script>alert('Account not found for this email'); window.location.href='../accountPage.php';</script>";
        exit;
    }

    // 2) Encrypt the new secondary email
    $enc_secondary = encrypt_field($email);

    // 3) Update DB using prepared statement
    $stmt = $conn->prepare("UPDATE users SET secondary_email = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $enc_secondary, $userId);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Secondary Email edit successfully'); window.location.href='../accountPage.php';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to update secondary email'); window.location.href='../accountPage.php';</script>";
        exit;
    }
}
?>
