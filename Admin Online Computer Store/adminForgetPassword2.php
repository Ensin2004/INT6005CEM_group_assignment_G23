<?php
session_start();
require_once "includes/csrf.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Verify OTP</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/forgetPassword.css">
</head>

<body>
    <?php
    session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
    ]);

    session_start();
    require_once "includes/dbh.inc.php";

    // Collect form data
    $email = htmlspecialchars($_POST["AdminEmail"]);
    $newPassword = htmlspecialchars($_POST["newPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

    // Generate OTP
    $otp = rand(10000, 99999);

    // Send OTP via email
    $to = $email;
    $from = "kahtechpng@gmail.com";
    $fromName = "KAH TECH Admin";
    $message = $otp . " is your OTP to reset your admin password.";
    $subject = "KAH TECH Admin Password Reset Verification";
    $header = 'From: ' . $fromName . ' <' . $from . '>';

    mail($to, $subject, $message, $header);
    ?>

    <?php include 'header.php'; ?>

    <main>
        <div class="loginDisplay">
            <form class="changePasswordBox" action="includes/adminForgetPasswordOtpCheck.php" method="post">
                <?php createCSRFInput(); ?>
                <div class="loginLogo">
                    <p>Admin Password Reset</p>
                    <img src="../Image/logo.png" alt="KAH TECH Logo">
                </div>

                <div class="loginInfo">
                    <label for="Email">Admin Email:</label>
                    <input required type="email" id="Email" name="AdminEmail" placeholder="Email"
                        value="<?php echo $email ?>" readonly>

                    <label for="newPassword">New Password:</label>
                    <input required type="password" id="newPassword" name="newPassword"
                        placeholder="Enter New Password" value="<?php echo $newPassword ?>" readonly>

                    <label for="confirmPassword">Confirm Password:</label>
                    <input required type="password" id="confirmPassword" name="confirmPassword"
                        placeholder="Confirm Password" value="<?php echo $confirmPassword ?>" readonly>
                    <p class="pwd_confirmation" id="pwd_confirmation">Password not match</p>

                    <label for="otp_inp">Enter OTP:</label>
                    <input type="text" id="otp_inp" name="otp_inp" placeholder="Enter OTP" required>

                    <input type="hidden" id="otp" name="otp" value="<?php echo $otp; ?>">

                    <a class="sendAgain" href="#" onclick="window.location.reload();">Send OTP Again</a>
                    <button class="logIn" type="submit">Verify OTP</button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>
