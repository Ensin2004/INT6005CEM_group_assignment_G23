<?php
require_once "dbh.inc.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $firstEmail      = $_POST["primaryEmail"]     ?? '';
    $email           = $_POST["secondaryEmail"]   ?? '';
    $confirmPassword = $_POST["confirmPassword"]  ?? '';
    $otp             = $_POST["otp"]              ?? '';
    $otpVerify       = $_POST["otp_inp"]          ?? '';
    $expiresAt       = (int)($_POST["otp_expires_at"] ?? 0);

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

    // includes/secondary_email_verification.php
    if ($otpVerify !== $otp) {
      // RIGHT: auto-POST back so OTP is not in the URL bar
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



          
        
    } else {
        // Prepare SQL statement to prevent SQL injection
        mysqli_query($conn, "UPDATE users SET secondary_email = '$email' WHERE email = '$firstEmail'");
        echo "<script>alert('Secondary Email edit successfully'); window.location.href='../accountPage.php';</script>";
    }
}
