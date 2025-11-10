<?php
require_once "includes/security.php";
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
        $image = '../Image/no_img_customer.png';
        if (!empty($row['user_image'])) {
            $image = 'image/' . $row['user_image'];
        }
        ?>

        <!-- Biggest box in body to set background -->
        <div class="accDisplay">
            <!-- Set up sign up content -->
            <form class="accBox" action="includes/checkSecondaryEmail.php" method="post" enctype="multipart/form-data">
                <div class="signUpLogo">

                    <div class="img_container">
                        <img class="img_preview" src="<?php echo $image; ?>" id="acc_preview">
                    </div>
                </div>
                <div class="accInfo">
                    <label for="primaryEmail">Email :</label>
                    <input required type="email" id="Email" name="primaryEmail" value="<?php echo $row['email'] ?>" readonly>

                    <label for="SecondaryEmail">Secondary Email (Optional) :</label>
                    <div class="secondaryEmail">
                        <input required type="email" id="SecondaryEmail" name="secondaryEmail" value="<?php echo $row['secondary_email'] ?>">

                    </div>
                    <label for="confirmPassword"> Confirm Password</label>
                    <input required type="password" id="confirmPassword" name="confirmPassword" placeholder="enter your password">
                    <button class="acc" type="submit">Update</button>


                </div>
            </form>
        </div>
    </main>

    <?php
    include 'footer.php';
    ?>


</body>

</html>