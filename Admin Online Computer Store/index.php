<?php
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start();
require_once "includes/csrf.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Log In</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <main>
        <div class="loginDisplay">
            <form class="loginBox" action="includes/loginuser.php" method="post">
                <?php createCSRFInput(); ?>
                <div class="loginLogo">
                    <p>Welcome to</p>
                    <img src="../Image/logo.png" alt="KAH TECH Logo">
                </div>
                <div class="loginInfo">
                    <label for="Username">Name :</label>
                    <input required type="text" id="Username" name="Username" maxlength="50" placeholder="Username">
                    <p class="limit-warning" id="nameLimit">Character limit reached (50)</p>

                    <label for="Email">Email :</label>
                    <input required type="email" id="Email" name="UserEmail" maxlength="100" placeholder="Email">
                    <p class="limit-warning" id="emailLimit">Character limit reached (100)</p>

                    <label for="Password">Password :</label>
                    <input required type="password" id="Password" name="UserPassword" maxlength="20" placeholder="Password">
                    <p class="limit-warning" id="pwdLimit">Character limit reached (20)</p>

                    <p class="forgetPass"><a class="forgetPassBtn" href="adminForgetPassword.php">Forget Password?</a></p>

                    <button class="logIn" type="submit" name="login" value="Login">Log In</button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        // Field Limit Warning
        function setupLimitWarning(inputId, warningId, max) {
            const input = document.getElementById(inputId);
            const warning = document.getElementById(warningId);

            input.addEventListener('input', () => {
            if (input.value.length === max) {
                warning.style.display = "block";
            } else {
                warning.style.display = "none";
            }
            });
        }

        // Initialize limit warnings for all fields
        setupLimitWarning('Username', 'nameLimit', 50);
        setupLimitWarning('Email', 'emailLimit', 100);
        setupLimitWarning('Password', 'pwdLimit', 20);
    </script>
    
</body>
</html>
