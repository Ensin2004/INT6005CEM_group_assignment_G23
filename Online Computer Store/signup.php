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

    <main>

        <div class="signUpDisplay">
            <form id="signupForm" class="signUpBox" method="post" action="includes/checkUsername.php">
                <div class="signUpLogo">
                    <img src="../Image/logo.png" alt="KAH TECH Logo">
                </div>
                <div class="signUpInfo">
                    <label for="Username">Name :</label>
                    <input required type="text" id="Username" name="newUsername" placeholder="Enter Username">
                    <label for="Email">Email :</label>
                    <input required type="email" id="Email" name="newEmail" placeholder="Enter Email">
                    <label for="Phone">Phone Number :</label>
                    <input required type="text" id="Phone" name="newPhone" placeholder="Enter Phone Number">
                    <label for="Address">Address :</label>
                    <input required type="text" id="Address" name="newAddress" placeholder="Enter address">
                    <label for="newPassword">New Password :</label>
                    <input required type="password" id="newPassword" name="newPassword" placeholder="Enter New Password">
                    <div class="pwd_validation_container" id="pwd_validation_container">
                        <p>Password requirements: </p>
                        <p class="pwd_validation" id="pwd_character">* 8-20 <b>characters</b></p>
                        <p class="pwd_validation" id="pwd_letter">* at least one <b>letter (A-Z)</b></p>
                        <p class="pwd_validation" id="pwd_number">* at least one <b>number (0-9)</b></p>
                        <p class="pwd_validation" id="pwd_symbol">* at least one <b>special characters (@$!%*?&)</b></p>
                    </div>
                    <label for="confirmPassword">Confirm Password</label>
                    <input required type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                    <p class="pwd_confirmation" id="pwd_confirmation">Password not match</p>
                    <button class="signUp" type="submit" id="submit_btn" disabled>Sign up</button>
                    <p class="haveAcc">Already have an account? <a class="logIn" href="login.php">Log In</a>.</p>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        document.getElementById('newPassword').addEventListener('input', validatePassword);
        document.getElementById('confirmPassword').addEventListener('input', validatePassword);

        function validatePassword() {
            var password = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            var pwd_validation_container = document.getElementById('pwd_validation_container');
            var pwd_confirmation = document.getElementById('pwd_confirmation');
            var pwd_character = document.getElementById('pwd_character');
            var pwd_letter = document.getElementById('pwd_letter');
            var pwd_number = document.getElementById('pwd_number');
            var pwd_symbol = document.getElementById('pwd_symbol');
            var submit_btn = document.getElementById('submit_btn');

            const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

            if (!passwordRegex.test(password) || confirmPassword != password) {
                submit_btn.disabled = true;

                if (!passwordRegex.test(password)) {
                    pwd_validation_container.style.display = "block";
                    pwd_confirmation.style.display = "none";

                    if (password.length < 8 || password.length > 20) {
                        pwd_character.style.display = "block";
                    } else {
                        pwd_character.style.display = "none";
                    }

                    if (!/[a-zA-Z]/.test(password)) {
                        pwd_letter.style.display = "block";
                    } else {
                        pwd_letter.style.display = "none";
                    }

                    if (!/\d/.test(password)) {
                        pwd_number.style.display = "block";
                    } else {
                        pwd_number.style.display = "none";
                    }

                    if (!/[@$!%*?&]/.test(password)) {
                        pwd_symbol.style.display = "block";
                    } else {
                        pwd_symbol.style.display = "none";
                    }
                } else {
                    pwd_validation_container.style.display = "none";
                    pwd_confirmation.style.display = "block";
                }
            } else {
                pwd_validation_container.style.display = "none";
                pwd_confirmation.style.display = "none";
                submit_btn.disabled = false;
            }
        }
    </script>

</body>

</html>