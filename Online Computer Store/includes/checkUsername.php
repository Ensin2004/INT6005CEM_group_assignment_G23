<?php
require_once 'dbh.inc.php';
if (!$conn) {
    die("Database connection failed");
} else {
    //get variables
    $name = htmlspecialchars($_POST["newUsername"]);
    $email = htmlspecialchars($_POST["newEmail"]);
    $phone = htmlspecialchars($_POST["newPhone"]);
    $address = htmlspecialchars($_POST["newAddress"]);
    $password = htmlspecialchars($_POST["newPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

    $checkResult = mysqli_num_rows(mysqli_query($conn, "SELECT user_name FROM users WHERE LOWER(user_name) = LOWER('$name');"));
    $checkEmail = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM users WHERE LOWER(email) = LOWER('$email');"));
    $checkSecondaryEmail = mysqli_num_rows(mysqli_query($conn, "SELECT secondary_email FROM users WHERE LOWER(secondary_email) = LOWER('$email');"));
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