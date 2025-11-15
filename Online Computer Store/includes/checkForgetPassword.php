<?php
require_once 'dbh.inc.php';
require_once 'crypto.php';

if (!$conn) {
    die("Database connection failed");
} else {
    $email         = htmlspecialchars($_POST["UserEmail"]);
    $newPassword   = htmlspecialchars($_POST["newPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

    $checkEmail = 0;
    $res = mysqli_query($conn, "SELECT email, secondary_email FROM users");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $e1 = decrypt_field($row['email']);
            $e2 = decrypt_field($row['secondary_email']);
            if (strcasecmp($e1, $email) === 0 || strcasecmp($e2, $email) === 0) {
                $checkEmail = 1;
                break;
            }
        }
    }
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
    if ($checkEmail == 0) {
        echo "<script>alert('Email not exists'); window.history.go(-1);</script>";
        exit;
    } else {
    ?>
        <h1>Processing...</h1>
        <p style="font-size: 1.25rem;">Sending OTP to <b style="font-weight: bold;"><?php echo $email ?></b></p>
        <script>
            function proceed() {
                // Create a new form element
                var form = document.createElement('form');
                form.setAttribute('method', 'POST'); // Set method to POST
                form.setAttribute('action', '../forgetPassword2.php'); // Set action URL


                var input1 = document.createElement('input');
                input1.setAttribute('type', 'hidden');
                input1.setAttribute('name', 'UserEmail');
                input1.setAttribute('value', '<?php echo $email; ?>');

                var input2 = document.createElement('input');
                input2.setAttribute('type', 'hidden');
                input2.setAttribute('name', 'newPassword');
                input2.setAttribute('value', '<?php echo $newPassword; ?>');

                var input3 = document.createElement('input');
                input3.setAttribute('type', 'hidden');
                input3.setAttribute('name', 'confirmPassword');
                input3.setAttribute('value', '<?php echo $confirmPassword; ?>');

                // Append inputs to the form
                form.appendChild(input1);
                form.appendChild(input2);
                form.appendChild(input3);

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