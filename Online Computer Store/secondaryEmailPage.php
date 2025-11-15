<?php
require_once "includes/security.php";
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
    <link rel="stylesheet" href="css/secondaryEmail.css">
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
        $row = mysqli_fetch_assoc($result);

        $plain_primary   = decrypt_field($row['email']);
        $plain_secondary = decrypt_field($row['secondary_email']);

        $image = '../Image/no_img_customer.png';
        if (!empty($row['user_image'])) {
            $image = 'image/' . $row['user_image'];
        }
        ?>

        <div class="accDisplay">
            <form class="accBox" action="includes/checkSecondaryEmail.php" method="post">
                <div class="signUpLogo">
                    <div class="img_container">
                        <img class="img_preview" src="<?php echo $image; ?>" id="acc_preview">
                    </div>
                </div>
                <div class="accInfo">
                    <label for="primaryEmail">Email :</label>
                    <input required type="email" id="Email" name="primaryEmail"
                           value="<?php echo htmlspecialchars($plain_primary, ENT_QUOTES, 'UTF-8'); ?>" readonly>

                    <label for="SecondaryEmail">Secondary Email (Optional) :</label>
                    <div class="secondaryEmail">
                        <input required type="email" id="SecondaryEmail" name="secondaryEmail" maxlength="100"
                               value="<?php echo htmlspecialchars($plain_secondary, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <p class="limit-warning" id="emailLimit">Character limit reached (100)</p>
                    
                    <label for="confirmPassword"> Confirm Password</label>
                    <input required type="password" id="confirmPassword" name="confirmPassword" placeholder="enter your password">
                    <button class="acc" type="submit">Update</button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        // Field limit warning
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


        // warning
        setupLimitWarning('SecondaryEmail', 'emailLimit', 100);
    </script>

</body>

</html>
