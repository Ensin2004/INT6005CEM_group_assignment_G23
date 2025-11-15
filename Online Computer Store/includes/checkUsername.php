<?php
require_once 'dbh.inc.php';
require_once 'crypto.php';

if (!$conn) {
    die("Database connection failed");
}

$name     = htmlspecialchars($_POST["newUsername"]);
$email    = htmlspecialchars($_POST["newEmail"]);
$phone    = htmlspecialchars($_POST["newPhone"]);
$address  = htmlspecialchars($_POST["newAddress"]);
$password = htmlspecialchars($_POST["newPassword"]);
$confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

// Username check
$checkResult = 0;
$res = mysqli_query($conn, "SELECT user_name FROM users");
while ($row = mysqli_fetch_assoc($res)) {
    $u = decrypt_field($row['user_name']);
    if (strcasecmp($u, $name) === 0) {
        $checkResult = 1;
        break;
    }
}

// Email checks (primary + secondary)
$checkEmail = 0;
$checkSecondaryEmail = 0;
$res2 = mysqli_query($conn, "SELECT email, secondary_email FROM users");
while ($row = mysqli_fetch_assoc($res2)) {
    $e1 = decrypt_field($row['email']);
    $e2 = decrypt_field($row['secondary_email']);
    if (strcasecmp($e1, $email) === 0) {
        $checkEmail = 1;
    }
    if (strcasecmp($e2, $email) === 0) {
        $checkSecondaryEmail = 1;
    }
    if ($checkEmail || $checkSecondaryEmail) break;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>

    <?php
    if ($checkResult != 0) {
        echo "<script>alert('Username already exists'); window.history.go(-1);</script>";
        exit;
    }
    else if ($checkEmail != 0) {
        echo "<script>alert('Email already exists'); window.history.go(-1);</script>";
        exit;
    }
    else if ($checkSecondaryEmail != 0) {
        echo "<script>alert('Email already exists'); window.history.go(-1);</script>";
        exit;
    }
     else {
    ?>
        <h1>Processing...</h1>
        <p style="font-size: 1.25rem;">Sending OTP to <b style="font-weight: bold;"><?php echo $email?></b></p>
        <script>
            function proceed() {
                // Create a new form element
                var form = document.createElement('form');
                form.setAttribute('method', 'POST'); // Set method to POST
                form.setAttribute('action', '../signup2.php'); // Set action URL

                // Create hidden input elements
                var input1 = document.createElement('input');
                input1.setAttribute('type', 'hidden');
                input1.setAttribute('name', 'newUsername');
                input1.setAttribute('value', '<?php echo $name; ?>');

                var input2 = document.createElement('input');
                input2.setAttribute('type', 'hidden');
                input2.setAttribute('name', 'newEmail');
                input2.setAttribute('value', '<?php echo $email; ?>');

                var input3 = document.createElement('input');
                input3.setAttribute('type', 'hidden');
                input3.setAttribute('name', 'newPhone');
                input3.setAttribute('value', '<?php echo $phone; ?>');

                var input4 = document.createElement('input');
                input4.setAttribute('type', 'hidden');
                input4.setAttribute('name', 'newAddress');
                input4.setAttribute('value', '<?php echo $address; ?>');

                var input5 = document.createElement('input');
                input5.setAttribute('type', 'hidden');
                input5.setAttribute('name', 'newPassword');
                input5.setAttribute('value', '<?php echo $password; ?>');

                var input6 = document.createElement('input');
                input6.setAttribute('type', 'hidden');
                input6.setAttribute('name', 'confirmPassword');
                input6.setAttribute('value', '<?php echo $confirmPassword; ?>');

                // Append inputs to the form
                form.appendChild(input1);
                form.appendChild(input2);
                form.appendChild(input3);
                form.appendChild(input4);
                form.appendChild(input5);
                form.appendChild(input6);

                // Append the form to the document body
                document.body.appendChild(form);

                // Submit the form
                form.submit();
            }

            proceed();
        </script>
    <?php
    }
    ?>
</body>

</html>