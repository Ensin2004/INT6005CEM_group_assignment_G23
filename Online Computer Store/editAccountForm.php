<?php
require_once "includes/security.php";
require_once "includes/csrf.php";
require_once "includes/crypto.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Account</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/editAccount.css">
</head>

<body>

    <?php
    include 'header.php';
    ?>

    <main>
        <?php
        include 'includes/dbh.inc.php';
        $id = $_SESSION['ID'];

        $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
        $row    = mysqli_fetch_assoc($result);

        $plain_name       = decrypt_field($row['user_name']);
        $plain_email      = decrypt_field($row['email']);
        $plain_secondary  = decrypt_field($row['secondary_email']);
        $plain_phone      = decrypt_field($row['phone']);
        $plain_address    = decrypt_field($row['user_address']); 

        $image = '../Image/no_img_customer.png';
        if (!empty($row['user_image'])) {
            $image = 'image/' . $row['user_image'];
        }
        ?>

        <!-- Biggest box in body to set background -->
        <div class="accDisplay">
            <!-- Set up sign up content -->
            <form class="accBox" action="includes/updateUserAccount.php" method="post" enctype="multipart/form-data">
                <?php createCSRFInput(); ?>
                <div class="signUpLogo">
                    
                    <div class="img_container">
                        <img class="img_preview" src="<?php echo $image; ?>" id="acc_preview">
                        <label class="label" for="accountimg"></label>
                        <input class="imageInput" type="file" id="accountimg" name="accountimg"
                            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            onchange="showPreview(event, 'acc_preview', '<?php echo $image; ?>');">
                    </div>
                </div>
                <div class="accInfo">
                    <label for="Username">Name :</label>
                    <input required type="text" id="Username" name="newUsername" maxlength="50"
                       value="<?php echo htmlspecialchars($plain_name, ENT_QUOTES, 'UTF-8'); ?>">                  <p class="limit-warning" id="nameLimit">Character limit reached (50)</p>
                    <p class="validation-error" id="nameError">Please enter a valid name (letters only).</p>
                    
                    <label for="Email">Email :</label>
                    <input required type="email" id="Email" name="newEmail"
                       value="<?php echo htmlspecialchars($plain_email, ENT_QUOTES, 'UTF-8'); ?>" readonly>                    
                    
                    <label for="SecondaryEmail">Secondary Email (Optional) :</label>
                    <div class="secondaryEmail">
                        <input required type="email" id="SecondaryEmail" name="secondaryEmail"
                            value="<?php echo htmlspecialchars($plain_secondary, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        <a href="secondaryEmailPage.php" class="editBtn" id="editSecondary">Edit</a>
                    </div>
                    
                    <label for=" Phone">Phone Number :</label>
                    <input required type="text" id="Phone" name="newPhone" maxlength="11"
                       value="<?php echo htmlspecialchars($plain_phone, ENT_QUOTES, 'UTF-8'); ?>">                    <p class="limit-warning" id="phoneLimit">Character limit reached (11)</p>
                    <p class="validation-error" id="phoneError">Please enter a valid Malaysian phone number (starting with 01).</p>
                    
                    <label for="Address">Address :</label>
                    <input required type="text" id="Address" name="newAddress" maxlength="200"
                       value="<?php echo htmlspecialchars($plain_address, ENT_QUOTES, 'UTF-8'); ?>">
                    <p class="limit-warning" id="addressLimit">Character limit reached (200)</p>
                    
                    <label for="newPassword">New Password :</label>
                    <input type="password" id="newPassword" name="newPassword" maxlength="20" placeholder="Enter new password">
                    <p class="limit-warning" id="newPwdLimit">Character limit reached (20)</p>
                    <div class="pwd_validation_container" id="pwd_validation_container">
                        <p>Password requirements: </p>
                        <p class="pwd_validation" id="pwd_character">* 8-20 <b>characters</b></p>
                        <p class="pwd_validation" id="pwd_letter">* at least one <b>letter (A-Z)</b></p>
                        <p class="pwd_validation" id="pwd_number">* at least one <b>number (0-9)</b></p>
                        <p class="pwd_validation" id="pwd_symbol">* at least one <b>special characters (@$!%*?&)</b></p>
                        <p class="pwd_validation" id="pwd_space">* no <b>spaces allowed</b></p>
                    </div>
                    
                    <label for="confirmPassword"> Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" maxlength="20" placeholder="Re-enter new password">
                    <p class="limit-warning" id="confirmPwdLimit">Character limit reached (20)</p>
                    <p class="pwd_confirmation" id="pwd_confirmation">Password not match</p>
                    
                    <button class="acc" type="submit" id="submit_btn">Update</button>


                </div>
            </form>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>
    <script>
        function showPreview(event, previewId, originalSrc) {
            if (event.target.files.length > 0) {
                var file = event.target.files[0];
                if (file.size <= 1024 * 1024) {
                    var preview = document.getElementById(previewId);
                    preview.src = URL.createObjectURL(file);
                } else {
                    alert("Image size exceeds the limit (1MB)");
                    event.target.value = "";
                    document.getElementById(previewId).src = originalSrc;
                }
            }
        }

        // ------------- Global validity flags -------------
        let isNameValid = false;
        let isEmailValid = false;
        let isPhoneValid = false;
        let isPasswordValid = false;

        const submit_btn = document.getElementById('submit_btn');
        const nameInput  = document.getElementById('Username');
        const emailInput = document.getElementById('Email');
        const phoneInput = document.getElementById('Phone');

        // ------------- Helper to control submit button -------------
        function updateSubmitButton() {
            if (isEmailValid && isPasswordValid) {
                submit_btn.disabled = false;
            } else {
                submit_btn.disabled = true;
            }
        }

        // ------------- Name validation -------------
        function validateName() {
            const name = nameInput.value.trim();
            const error = document.getElementById('nameError');

            // Letters, spaces, apostrophes, dots, hyphens; length 2–50
            const nameRegex = /^[A-Za-z\s'.-]{2,50}$/;

            if (name.length === 0) {
                error.style.display = "none";
                isNameValid = false;
            } else if (nameRegex.test(name)) {
                error.style.display = "none";
                isNameValid = true;
            } else {
                error.style.display = "block";
                isNameValid = false;
            }

            updateSubmitButton();
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

        // ------------- Malaysian phone validation -------------
        function validatePhone() {
            const phone = phoneInput.value.trim();
            const error = document.getElementById('phoneError');

            // Simple Malaysian format:
            // - starts with 0
            // - total 10 or 11 digits (e.g. 0123456789, 01234567890, 0421234567)
            const phoneRegex = /^01\d{8,9}$/;

            if (phone.length === 0) {
                error.style.display = "none";
                isPhoneValid = false;
            } else if (phoneRegex.test(phone)) {
                error.style.display = "none";
                isPhoneValid = true;
            } else {
                error.style.display = "block";
                isPhoneValid = false;
            }

            updateSubmitButton();
        }

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
        setupLimitWarning('Phone', 'phoneLimit', 11);
        setupLimitWarning('Address', 'addressLimit', 200);
        setupLimitWarning('newPassword', 'newPwdLimit', 20);
        setupLimitWarning('confirmPassword', 'confirmPwdLimit', 20);

        // ------------- Attach validation listeners -------------
        nameInput.addEventListener('input', validateName);
        emailInput.addEventListener('input', validateEmail);
        phoneInput.addEventListener('input', validatePhone);
    </script>


</body>

</html>