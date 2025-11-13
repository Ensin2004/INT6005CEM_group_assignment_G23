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
                    <input required type="text" id="Username" name="newUsername" maxlength="50" placeholder="Enter Username">
                    <p class="limit-warning" id="nameLimit">Character limit reached (50)</p>

                    <label for="Email">Email :</label>
                    <input required type="email" id="Email" name="newEmail" maxlength="100" placeholder="Enter Email">
                    <p class="limit-warning" id="emailLimit">Character limit reached (100)</p>

                    <label for="Phone">Phone Number :</label>
                    <input required type="text" id="Phone" name="newPhone" maxlength="11" placeholder="Enter Phone Number">
                    <p class="limit-warning" id="phoneLimit">Character limit reached (11)</p>

                    <label for="Address">Address :</label>
                    <input required type="text" id="Address" name="newAddress" maxlength="200" placeholder="Enter Address">
                    <p class="limit-warning" id="addressLimit">Character limit reached (200)</p>

                    <label for="newPassword">New Password :</label>
                    <input required type="password" id="newPassword" name="newPassword" maxlength="20" placeholder="Enter New Password">
                    <p class="limit-warning" id="newPwdLimit">Character limit reached (20)</p>
                    <div class="pwd_validation_container" id="pwd_validation_container">
                        <p>Password requirements: </p>
                        <p class="pwd_validation" id="pwd_character">* 8-20 <b>characters</b></p>
                        <p class="pwd_validation" id="pwd_letter">* at least one <b>letter (A-Z)</b></p>
                        <p class="pwd_validation" id="pwd_number">* at least one <b>number (0-9)</b></p>
                        <p class="pwd_validation" id="pwd_symbol">* at least one <b>special characters (@$!%*?&)</b></p>
                        <p class="pwd_validation" id="pwd_space">* no <b>spaces allowed</b></p>
                    </div>

                    <label for="confirmPassword">Confirm Password</label>
                    <input required type="password" id="confirmPassword" name="confirmPassword" maxlength="20" placeholder="Confirm Password">
                    <p class="limit-warning" id="confirmPwdLimit">Character limit reached (20)</p>
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
            var pwd_space = document.getElementById('pwd_space');
            var submit_btn = document.getElementById('submit_btn');

            const passwordRegex = /^(?!.*\s)(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

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

                    const hasSpace = /\s/.test(password);
                    pwd_space.style.display = "block";
                    pwd_space.style.color = hasSpace ? "red" : "inherit";

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
        setupLimitWarning('Phone', 'phoneLimit', 11);
        setupLimitWarning('Address', 'addressLimit', 200);
        setupLimitWarning('newPassword', 'newPwdLimit', 20);
        setupLimitWarning('confirmPassword', 'confirmPwdLimit', 20);
    </script>

</body>

</html>