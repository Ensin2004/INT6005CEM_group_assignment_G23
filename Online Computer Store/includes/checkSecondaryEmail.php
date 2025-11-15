<?php
require_once 'dbh.inc.php';
require_once 'crypto.php';

if (!$conn) {
    die("Database connection failed");
} else {
    $firstEmail      = htmlspecialchars($_POST["primaryEmail"]);
    $email           = htmlspecialchars($_POST["secondaryEmail"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

    // -----------------------------------------------------
    // 1) Find the user whose PRIMARY email matches $firstEmail
    // -----------------------------------------------------
    $userRow = null;
    $res = mysqli_query($conn, "SELECT id, email, secondary_email, pwd FROM users");
    if (!$res) {
        die("Query failed");
    }

    while ($row = mysqli_fetch_assoc($res)) {
        $dec_email = decrypt_field($row['email']);
        if (strcasecmp($dec_email, $firstEmail) === 0) {
            $userRow = $row;
            break;
        }
    }

    if ($userRow === null || !password_verify($confirmPassword, $userRow['pwd'])) {
        echo "<script> alert('Wrong password'); window.history.go(-1); </script>";
        exit;
    }

    // -----------------------------------------------------
    // 2) Check if new secondary email already exists
    //    as primary or secondary for ANY user
    // -----------------------------------------------------
    $emailExists = false;
    $res2 = mysqli_query($conn, "SELECT email, secondary_email FROM users");
    if ($res2) {
        while ($row = mysqli_fetch_assoc($res2)) {
            $e1 = decrypt_field($row['email']);
            $e2 = decrypt_field($row['secondary_email']);
            if (strcasecmp($e1, $email) === 0 || strcasecmp($e2, $email) === 0) {
                $emailExists = true;
                break;
            }
        }
    }

    $checkEmail = $emailExists ? 1 : 0;
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
