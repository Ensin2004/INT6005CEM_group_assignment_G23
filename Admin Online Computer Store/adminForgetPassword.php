<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH Admin - Forgot Password</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/forgetPassword.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <div class="loginDisplay">
            <form class="loginBox" action="includes/checkAdminForgetPassword.php" method="post">
                <div class="loginLogo">
                    <p>Admin Password Reset</p>
                    <img src="../Image/logo.png" alt="KAH TECH Logo">
                </div>

                <div class="loginInfo">
                    <label for="Email">Admin Email:</label>
                    <input required type="email" id="Email" name="AdminEmail" maxlength="100" placeholder="Enter your email">
                    <p class="limit-warning" id="nameLimit">Character limit reached (100)</p>
                    <p class="validation-error" id="emailError">Please enter a valid email address.</p>

                    <label for="newPassword">New Password:</label>
                    <div class="pwd-wrapper">
                        <input required type="password" id="newPassword" name="newPassword" maxlength="20" placeholder="Enter New Password">
                        <i class="fa-solid fa-eye-slash toggle-eye" onclick="togglePassword('newPassword', this)"></i>
                    </div>
                    <p class="limit-warning" id="nameLimit">Character limit reached (20)</p>
                    <div class="pwd_validation_container" id="pwd_validation_container">
                        <p>Password requirements: </p>
                        <p class="pwd_validation" id="pwd_character">* 8-20 <b>characters</b></p>
                        <p class="pwd_validation" id="pwd_letter">* at least one <b>letter (A-Z)</b></p>
                        <p class="pwd_validation" id="pwd_number">* at least one <b>number (0-9)</b></p>
                        <p class="pwd_validation" id="pwd_symbol">* at least one <b>special character (@$!%*?&)</b></p>
                        <p class="pwd_validation" id="pwd_space">* no <b>spaces allowed</b></p>
                    </div>

                    <label for="confirmPassword">Confirm Password:</label>
                    <div class="pwd-wrapper">
                        <input required type="password" id="confirmPassword" name="confirmPassword" maxlength="20" placeholder="Confirm Password">
                        <i class="fa-solid fa-eye-slash toggle-eye" onclick="togglePassword('confirmPassword', this)"></i>
                    </div>
                    <p class="limit-warning" id="confirmPwdLimit">Character limit reached (20)</p>
                    <p class="pwd_confirmation" id="pwd_confirmation">Passwords do not match</p>

                    <button class="logIn" id="submit_btn" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }

        // ------------- Global validity flags -------------
        let isEmailValid = false;
        let isPasswordValid = false;

        const submit_btn = document.getElementById('submit_btn');
        const emailInput = document.getElementById('Email');

        // ------------- Helper to control submit button -------------
        function updateSubmitButton() {
            if (isEmailValid && isPasswordValid) {
                submit_btn.disabled = false;
            } else {
                submit_btn.disabled = true;
            }
        }

        // ------------- Email validation -------------
        function validateEmail() {
            const error = document.getElementById('emailError');
            const email = emailInput.value.trim();

            if (email.length === 0) {
                error.style.display = "none";
                isEmailValid = false;
            } else if (emailInput.validity.valid) {
                error.style.display = "none";
                isEmailValid = true;
            } else {
                error.style.display = "block";
                isEmailValid = false;
            }

            updateSubmitButton();
        }

        // Password validation (same logic as user version)
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

            if (!passwordRegex.test(password) || confirmPassword !== password) {
                submit_btn.disabled = true;

                if (!passwordRegex.test(password)) {
                    pwd_validation_container.style.display = "block";
                    pwd_confirmation.style.display = "none";

                    pwd_character.style.display = password.length < 8 || password.length > 20 ? "block" : "none";
                    pwd_letter.style.display = /[a-zA-Z]/.test(password) ? "none" : "block";
                    pwd_number.style.display = /\d/.test(password) ? "none" : "block";
                    pwd_symbol.style.display = /[@$!%*?&]/.test(password) ? "none" : "block";

                    const hasSpace = /\s/.test(password);
                    pwd_space.style.display = "block";
                    pwd_space.style.color = hasSpace ? "red" : "inherit";
                } else {
                    pwd_validation_container.style.display = "none";
                    pwd_confirmation.style.display = "block";
                }

                isPasswordValid = false;

            } else {
                pwd_validation_container.style.display = "none";
                pwd_confirmation.style.display = "none";
                submit_btn.disabled = false;
            }

            updateSubmitButton();
        }

        // ------------- Field Limit Warning (your existing code) -------------
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
        setupLimitWarning('Email', 'emailLimit', 100);
        setupLimitWarning('newPassword', 'newPwdLimit', 20);
        setupLimitWarning('confirmPassword', 'confirmPwdLimit', 20);

        // ------------- Attach validation listeners -------------
        emailInput.addEventListener('input', validateEmail);
    </script>
</body>

</html>
