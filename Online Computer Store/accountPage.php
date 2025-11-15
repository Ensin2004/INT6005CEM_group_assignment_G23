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
    <link rel="stylesheet" href="css/accountPage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <div class=accDisplay>
            <div class="accBox">
                <!-- Welcome box -->
                <div class="FirstAccBox">
                    <P>My Account</P>
                </div>

                <!-- content box-->
                <div class="MainAccBox">
                    <div class="accImage">
                        <?php
                            require_once "includes/dbh.inc.php";
                            $id = $_SESSION['ID'];

                            $queryImg = mysqli_query($conn, "SELECT user_image FROM users WHERE id = '$id'");
                            $rowImg = $queryImg ? mysqli_fetch_assoc($queryImg) : null;

                            $result = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
                            $row    = $result ? mysqli_fetch_assoc($result) : null;

                            $plain_name    = $row ? decrypt_field($row['user_name']) : '';
                            $plain_email   = $row ? decrypt_field($row['email']) : '';
                            $plain_phone   = $row ? decrypt_field($row['phone']) : '';
                            $plain_address = $row ? decrypt_field($row['user_address']) : ''; 
                        ?>
                        <img class="accountImg" src="image/<?php echo htmlspecialchars($rowImg['user_image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div class="showAccountDetails">
                        <div style="margin-bottom: 5px;">
                            <label for="Username">Name :</label>
                            <input required type="text" id="Username"
                                value="<?php echo htmlspecialchars($plain_name, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label for="Email">Email :</label>
                            <input required type="email" id="Email"
                                value="<?php echo htmlspecialchars($plain_email, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label for="Phone">Phone Number :</label>
                            <input required type="text" id="Phone"
                                value="<?php echo htmlspecialchars($plain_phone, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>
                        <div style="margin-bottom: 25px;">
                            <label for="Address">Address :</label>
                            <input required type="text" id="Address"
                                value="<?php echo htmlspecialchars($plain_address, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>
                        <div class="buttons">
                            <a class="editButton" href="editAccountForm.php">EDIT</a>
                            <a class="logOutButton" href="includes/logoutAccount.php">LOG OUT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php
    include 'footer.php';
    ?>
</body>

</html>