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
                    <input required type="text" id="Username" name="Username" placeholder="Username">
                    <label for="Email">Email :</label>
                    <input required type="email" id="Email" name="UserEmail" placeholder="Email">
                    <label for="Password">Password :</label>
                    <input required type="password" id="Password" name="UserPassword" placeholder="Password">
                    <p class="forgetPass"><a class="forgetPassBtn" href="forgetPassword.php">Forget Password?</a></p>
                    
                    <button class="logIn" type="submit" name="login" value="Login">Log In</button>
                    <!-- create account -->
                    <p class="noAcc">No account? <a class="signUp" href="signup.php">Sign Up</a> now.</p>
                </div>
            </form>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>
</body>

</html>