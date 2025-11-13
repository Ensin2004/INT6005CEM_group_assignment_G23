<?php
require_once "includes/security.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    echo "<script>alert('Access denied. Super Admins only.'); window.location.href='home.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KAH TECH Admin - Add Manager</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/addManager.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <form class="item_form" action="includes/addManager.inc.php" method="POST" id="addManagerForm">
            <div class="item_details">
                <div class="details">
                    <label for="admin_name">Name</label>
                    <p>:</p>
                    <div class="input_wrapper">
                        <input type="text" id="admin_name" name="admin_name" maxlength="50" required placeholder="Manager Username">
                        <p class="limit-warning" id="nameLimit">Character limit reached (50)</p>
                    </div>
                </div>
            
                <div class="details">
                    <label for="admin_email">Email</label>
                    <p>:</p>
                    <div class="input_wrapper">
                        <input type="email" id="admin_email" name="admin_email" maxlength="100" required placeholder="Manager Email">
                        <p class="limit-warning" id="emailLimit">Character limit reached (100)</p>
                    </div>
                </div>

                <div class="details">
                    <label for="admin_pwd">Password</label>
                    <p>:</p>
                    <div class="input_wrapper">
                        <input type="password" id="admin_pwd" name="admin_pwd" maxlength="20" required placeholder="Enter password">
                        <p class="limit-warning" id="newPwdLimit">Character limit reached (20)</p>
                    </div>
                </div>
                <div class="pwd_validation_container" id="pwd_validation_container">
                    <p>Password requirements: </p>
                    <p class="pwd_validation" id="pwd_character">* 8-20 <b>characters</b></p>
                    <p class="pwd_validation" id="pwd_letter">* at least one <b>letter (A-Z)</b></p>
                    <p class="pwd_validation" id="pwd_number">* at least one <b>number (0-9)</b></p>
                    <p class="pwd_validation" id="pwd_symbol">* at least one <b>special character (@$!%*?&)</b></p>
                    <p class="pwd_validation" id="pwd_space">* no <b>spaces allowed</b></p>
                </div>
            </div>

            <button class="submit_button" type="submit" name="addManager" id="submit_btn" disabled>ADD MANAGER</button>
        </form>
    </main>

    <?php include 'footer.php'; ?>

    <script src="js/sessionTimeout.js"></script>

    <script>
        document.getElementById('admin_pwd').addEventListener('input', validatePassword);

        function validatePassword() {
            var password = document.getElementById('admin_pwd').value;
            var pwd_validation_container = document.getElementById('pwd_validation_container');
            var pwd_character = document.getElementById('pwd_character');
            var pwd_letter = document.getElementById('pwd_letter');
            var pwd_number = document.getElementById('pwd_number');
            var pwd_symbol = document.getElementById('pwd_symbol');
            var pwd_space = document.getElementById('pwd_space');
            var submit_btn = document.getElementById('submit_btn');

            const passwordRegex = /^(?!.*\s)(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

            // Disable submit if don't match
            if (!passwordRegex.test(password)) {
                submit_btn.disabled = true;
                pwd_validation_container.style.display = "block";

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
        setupLimitWarning('admin_name', 'nameLimit', 50);
        setupLimitWarning('admin_email', 'emailLimit', 100);
        setupLimitWarning('admin_pwd', 'newPwdLimit', 20);
    </script>
</body>
</html>
