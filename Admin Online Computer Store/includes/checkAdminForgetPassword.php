<?php
require_once 'dbh.inc.php';
if (!$conn) {
    die("Database connection failed");
} else {
    // Get variables
    $email = htmlspecialchars($_POST["AdminEmail"]);
    $newPassword = htmlspecialchars($_POST["newPassword"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

    // Check if email exists in the admins table
    $checkEmail = mysqli_num_rows(mysqli_query(
        $conn,
        "SELECT admin_email FROM admins WHERE LOWER(admin_email) = LOWER('$email');"
    ));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset</title>
</head>

<body>

    <?php
    if ($checkEmail == 0) {
        echo "<script>alert('Email not found in admin records'); window.history.go(-1);</script>";
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
                form.setAttribute('action', '../adminForgetPassword2.php'); // Next step

                var input1 = document.createElement('input');
                input1.setAttribute('type', 'hidden');
                input1.setAttribute('name', 'AdminEmail');
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
