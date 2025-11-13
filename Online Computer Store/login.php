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
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <?php
    include 'header.php';
    ?>

    <main>
        <!-- Biggest box in body to set background -->
        <div class="loginDisplay">
            <!-- Set up login content -->
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

                    <p class="forgetPass"><a class="forgetPassBtn" href="forgetPassword.php">Forget Password?</a></p>
                    
                    <button class="logIn" type="submit" name="login" value="Login">Log In</button>
                    <!-- create account -->
                    <p class="noAcc">No account? <a class="signUp" href="signup.php">Sign Up</a> now.</p>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php';?>

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