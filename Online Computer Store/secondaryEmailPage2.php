<?php
require_once "includes/security.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Account</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/secondaryEmail.css">
</head>

<body>

    <?php
    include 'header.php';
    ?>

    <main>
        <?php
        // Collect form data

        $id = $_SESSION['ID'];
        $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
        $row = mysqli_fetch_assoc($result);
        $image = '../Image/no_img_customer.png';
        if (!empty($row['user_image'])) {
            $image = 'image/' . $row['user_image'];
        }
        $noAutoSend   = isset($_POST['no_autosend']) || isset($_GET['no_autosend']);
        $otp = $_POST['otp'] ?? ($_GET['otp'] ?? null);
        $otpExpiresAt = $_POST['otp_expires_at'] ?? ($_GET['otp_expires_at'] ?? null);

        $firstEmail      = htmlspecialchars($_POST['primaryEmail']     ?? $_GET['primaryEmail']     ?? '');
        $email           = htmlspecialchars($_POST['secondaryEmail']   ?? $_GET['secondaryEmail']   ?? '');
        $confirmPassword = htmlspecialchars($_POST['confirmPassword']  ?? $_GET['confirmPassword']  ?? '');
        
        

        // Send OTP via email
        $to = $email;
        $from = "kahtechpng@gmail.com";
        $fromName = "KAHTECH";
        $subject = "Secondary Email Verification";
        $header = 'From: ' . $fromName . ' <' . $from . '>';

      if ((($otp === null) && !$noAutoSend) || isset($_POST['resend'])) {
    $otp = rand(10000, 99999);
    $otpExpiresAt = time() + 60; // seconds
    $message = $otp . " is your OTP.";
    @mail($to, $subject, $message, $header);
}




        ?>

        <!-- Biggest box in body to set background -->
        <div class="accDisplay">
            <!-- Set up sign up content -->
            <form class="accBox" action="includes/secondaryEmailVerification.php" method="post" enctype="multipart/form-data">
                <div class="signUpLogo">

                    <div class="img_container">
                        <img class="img_preview" src="<?php echo $image; ?>" id="acc_preview">
                    </div>
                </div>
                <div class="accInfo">
                    <label for="Email">Email :</label>
                    <input required type="email" id="Email" name="primaryEmail" value="<?php echo $row['email'] ?>" readonly>

                    <div class="secondaryEmail">
                        <input required type="email" id="SecondaryEmail" name="secondaryEmail" value="<?php echo $email; ?>" readonly>
                    </div>

                    <input type="hidden" name="confirmPassword" value="<?php echo $row['pwd'] ?>" readonly>

                    <label for="otp_inp">Enter OTP :</label>
                    <input required type="text" id="otp_inp" name="otp_inp" placeholder="Enter OTP">
                    <input type="hidden" id="otp" name="otp" value="<?php echo $otp; ?>">
                    <input type="hidden" id="otp_expires_at" name="otp_expires_at" value="<?php echo $otpExpiresAt; ?>">


                   <a id="resendBtn" class="sendAgain" href="#">
  Send OTP Again <span id="resendCountdown" style="margin-left:8px;"></span>
</a>

                    <button class="acc" type="submit">Verify OTP</button>




                </div>
            </form>
            <form id="resendForm" method="post" action="secondaryEmailPage2.php" style="display:none;">
  <input type="hidden" name="primaryEmail"    value="<?php echo $row['email']; ?>">
  <input type="hidden" name="secondaryEmail"  value="<?php echo $email; ?>">
  <input type="hidden" name="confirmPassword" value="<?php echo $row['pwd']; ?>">
  <input type="hidden" name="resend" value="1">
</form>

        </div>
    </main>
    <?php
        $alert = $_POST['alert'] ?? ($_GET['alert'] ?? '');
        if ($alert !== ''): ?>
        <script>alert('<?php echo htmlspecialchars($alert, ENT_QUOTES); ?>');
    </script>


    <?php endif; ?>
<script>
(function () {
  const COOLDOWN = 10; // seconds
  let remaining = COOLDOWN;
  let timerId = null;

  const btn  = document.getElementById('resendBtn');
  const form = document.getElementById('resendForm');
  const cdEl = document.getElementById('resendCountdown');

  if (!btn || !form || !cdEl) return;

  function setDisabled(disabled) {
    if (disabled) {
      btn.setAttribute('data-disabled', '1');
      btn.style.pointerEvents = 'none';
      btn.style.opacity = '0.5';
    } else {
      btn.removeAttribute('data-disabled');
      btn.style.pointerEvents = 'auto';
      btn.style.opacity = '1';
      cdEl.textContent = '';
    }
  }

  function tick() {
    if (remaining > 0) {
      setDisabled(true);
      cdEl.textContent = `(${remaining}s)`;
      remaining -= 1;
      timerId = setTimeout(tick, 1000);
    } else {
      setDisabled(false);
      timerId = null;
    }
  }

  // start cooldown on every load
  tick();

  btn.addEventListener('click', function (e) {
    e.preventDefault();                 // stop "#" navigation
    if (btn.getAttribute('data-disabled') === '1') return;

    // Don't start another countdown here.
    // Just disable UI and submit; the new page load will start fresh at 30s.
    setDisabled(true);
    cdEl.textContent = '(sending...)';
    if (timerId) { clearTimeout(timerId); timerId = null; }

    form.submit();
  });
})();
</script>




    <?php
    include 'footer.php';
    ?>


</body>

</html>