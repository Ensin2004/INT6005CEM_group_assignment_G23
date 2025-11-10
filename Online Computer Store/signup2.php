<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Sign Up</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/signup.css">
</head>

<body>

    <?php include 'header.php'; ?>
    <?php
    // Collect form data
    $name = htmlspecialchars($_POST["newUsername"]);
    $email = htmlspecialchars($_POST["newEmail"]);
    $phone = htmlspecialchars($_POST["newPhone"]);
    $address = htmlspecialchars($_POST["newAddress"]);
    $password = htmlspecialchars($_POST["newPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
    $otp = rand(10000, 99999);
    $otpExpiresAt = time() + 60;
    // Send OTP via email
    $to = $email;
    $from = "kahtechpng@gmail.com";
    $fromName = "KAHTECH";
    $message = $otp . " is your OTP.";
    $subject = "New Account Sign Up";
    $header = 'From: ' . $fromName . ' <' . $from . '>';

    mail($to, $subject, $message, $header)
    ?>

    <main>
        <div class="signUpDisplay">
            <form id="signupForm" class="signUpBox" method="post" action="includes/signupUser.php">
                <div class="signUpLogo">
                    <img src="../Image/logo.png" alt="KAH TECH Logo">
                </div>
                <div class="signUpInfo">
                    <label for="Username">Name :</label>
                    <input required type="text" id="Username" name="newUsername" value="<?php echo $name ?>" placeholder="Enter Username" readOnly>
                    <label for="Email">Email :</label>
                    <input required type="email" id="Email" name="newEmail" value="<?php echo $email ?>" placeholder="Enter Email" readOnly>
                    <label for="Phone">Phone Number :</label>
                    <input required type="text" id="Phone" name="newPhone" value="<?php echo $phone ?>" placeholder="Enter Phone Number" readOnly>
                    <label for="Address">Address :</label>
                    <input required type="text" id="Address" name="newAddress" value="<?php echo $address ?>" placeholder="Enter address" readOnly>
                    <label for="newPassword">New Password :</label>
                    <input required type="password" id="newPassword" name="newPassword" value="<?php echo $password ?>" placeholder="Enter New Password" readOnly>
                    <label for="confirmPassword">Confirm Password :</label>
                    <input required type="password" id="confirmPassword" name="confirmPassword" value="<?php echo $confirmPassword ?>" placeholder="Confirm Password" readOnly>
                    <label for="otp_inp">Enter OTP :</label>
                    <input type="text" id="otp_inp" name="otp_inp" placeholder="Enter OTP">
                    <input type="hidden" id="otp" name="otp" value="<?php echo $otp; ?>">
                    
                    <input type="hidden" name="otp_expires_at" value="<?php echo $otpExpiresAt; ?>">

                      <a id="resendBtn" class="sendAgain" href="#">
  Send OTP Again <span id="resendCountdown" style="margin-left:8px;"></span>
</a>
                    <button class="otpverifyButton" type="submit">Verify OTP</button>

                    <p class="haveAcc">Already have an account? <a class="logIn" href="login.php">Log In</a>.</p>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>
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