<?php
require_once "includes/csrf.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Log In</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/forgetPassword.css">
</head>

<body>
    <?php
    // Collect form data

    $email = htmlspecialchars($_POST["UserEmail"]);
    $newPassword = htmlspecialchars($_POST["newPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

    $otp = rand(10000, 99999);
    $otpExpiresAt = time() + 60;
    // Send OTP via email
    $to = $email;
    $from = "kahtechpng@gmail.com";
    $fromName = "KAHTECH";
    $message = $otp . " is your OTP.";
    $subject = "Secondary Email Verification";
    $header = 'From: ' . $fromName . ' <' . $from . '>';

    mail($to, $subject, $message, $header)
    ?>
    <?php
    include 'header.php';
    ?>

    <main>
        <!-- Biggest box in body to set background -->
        <div class="loginDisplay">
            <!-- Set up login content -->
            <form class="changePasswordBox" action="includes/forgetPasswordOtpCheck.php" method="post">
                <?php createCSRFInput(); ?>
                <div class="loginLogo">
                    <p>Welcome to</p>
                    <img src="../Image/logo.png" alt="KAH TECH Logo">
                </div>
                <div class="loginInfo">
                    <label for="Email">Email / Secondary Email:</label>
                    <input required type="email" id="Email" name="UserEmail" placeholder="Email" value="<?php echo $email ?>" readonly>
                    <label for="newPassword">New Password :</label>
                    <input required type="password" id="newPassword" name="newPassword" placeholder="Enter New Password" value="<?php echo $newPassword ?>" readonly>
                    <label for="confirmPassword">Confirm Password</label>
                    <input required type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" value="<?php echo $confirmPassword ?>" readonly>
                    <p class="pwd_confirmation" id="pwd_confirmation">Password not match</p>

                    <label for="otp_inp">Enter OTP :</label>
                    <input type="text" id="otp_inp" name="otp_inp" placeholder="Enter OTP">
                    <input type="hidden" id="otp" name="otp" value="<?php echo $otp; ?>">
                    <input type="hidden" name="otp_expires_at" value="<?php echo $otpExpiresAt; ?>">
                    <a id="resendBtn" class="sendAgain" href="#">
                    Send OTP Again <span id="resendCountdown" style="margin-left:8px;"></span>
                    </a>

                    <button class="logIn" type="submit">Verify OTP</button>


                </div>
            </form>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>
<script>
(function () {
  const COOLDOWN = 10;              // seconds
  const btn = document.getElementById('resendBtn');
  const cd  = document.getElementById('resendCountdown');
  if (!btn || !cd) return;

  let remaining = 0;
  let timerId = null;

  function setDisabled(disabled) {
    btn.dataset.disabled = disabled ? '1' : '0';
    btn.style.pointerEvents = disabled ? 'none' : 'auto';
    btn.style.opacity = disabled ? '0.5' : '1';
    if (!disabled) cd.textContent = '';
  }

  function tick() {
    cd.textContent = `(${remaining}s)`;
    if (remaining <= 0) {
      setDisabled(false);
      timerId = null;
      return;
    }
    remaining -= 1;
    timerId = setTimeout(tick, 1000);
  }

  function startCooldown() {
    remaining = COOLDOWN;
    setDisabled(true);
    if (timerId) clearTimeout(timerId);
    tick();
  }

  // start 30s cooldown on every page load
  startCooldown();

    btn.addEventListener('click', function(e){
    e.preventDefault();
    if (btn.dataset.disabled === '1') return;

    // optional: brief UI feedback
    setDisabled(true);
    cd.textContent = '(sending...)';

    // Reload the page (will prompt to re-submit if current page was POST)
    window.location.reload(); // or: window.location = window.location.href;
  });
})();
</script>


</body>

</html>