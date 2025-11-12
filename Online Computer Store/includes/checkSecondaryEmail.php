<?php
require_once 'dbh.inc.php';
if (!$conn) {
    die("Database connection failed");
} else {
    //get variables
    $firstEmail = htmlspecialchars($_POST["primaryEmail"]);
    $email = htmlspecialchars($_POST["secondaryEmail"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

    $checkEmail = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM users 
    WHERE LOWER(email) = LOWER('$email') OR LOWER(secondary_email) = LOWER('$email');"));
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$firstEmail'");
    $row = mysqli_fetch_assoc($query);

    if (mysqli_num_rows($query) == 0 || !password_verify($confirmPassword, $row['pwd'])) {
        echo "<script> alert('Wrong password'); window.history.go(-1); </script>";
        exit;
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
    if ($checkEmail != 0) {
        echo "<script>alert('Email already exists'); window.history.go(-1);</script>";
        exit;
    } else {
    ?>
        <h1>Processing...</h1>
        <p style="font-size: 1.25rem;">Sending OTP to <b style="font-weight: bold;"><?php echo $email ?></b></p>
        <script>
            (function proceed() {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '../secondaryEmailPage2.php';

                var a = document.createElement('input');
                a.type = 'hidden';
                a.name = 'primaryEmail';
                a.value = '<?php echo $firstEmail; ?>';

                var b = document.createElement('input');
                b.type = 'hidden';
                b.name = 'secondaryEmail';
                b.value = '<?php echo $email; ?>';

                var c = document.createElement('input');
                c.type = 'hidden';
                c.name = 'confirmPassword';
                c.value = '<?php echo $confirmPassword; ?>';

                form.appendChild(a);
                form.appendChild(b);
                form.appendChild(c);
                document.body.appendChild(form);
                form.submit();
            })();
        </script>

    <?php
    }
    ?>
</body>

</html>